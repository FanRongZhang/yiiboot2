<?php


namespace addons\autocellphone\api\controllers;

use api\controllers\OnAuthController;
use addons\autocellphone\common\models\Android;
use addons\autocellphone\common\models\Douyinqun;
use addons\autocellphone\common\models\Douyinuser;
use addons\autocellphone\common\models\Jihuoma;
use common\models\jd\Sku;
use GatewayWorker\Lib\Gateway;
use yii\db\Exception;


class AutojscodeController extends OnAuthController
{
    public $modelClass = Android::class;

    /**
     * @var array
     */
    protected $authOptional = ['*'];

    private function getJavascriptCodeMd5(){
        $tool = file_get_contents(\Yii::getAlias("@addons/autocellphone/autojs/tool.js"));
        $douyin = file_get_contents(\Yii::getAlias("@addons/autocellphone/autojs/douyin_v6.js"));
        return md5($tool . $douyin);
    }

    private function getJavascriptCode()
    {
        $codetime = date('Y-m-d H:i:s');
        $host = \Yii::$app->request->getHostName();

        $jihuoma = $this->getPageParam('jihuoma');

        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
        ]);
        $user_id = $modelJiHuoMa ? $modelJiHuoMa->user_id : '0';

        $tool = file_get_contents(\Yii::getAlias("@addons/autocellphone/autojs/tool.js"));
        $tool = str_replace("module.exports = tool", '', $tool);

        $douyin = file_get_contents(\Yii::getAlias("@addons/autocellphone/autojs/douyin_v6.js"));
        $douyin = str_replace("//toolcode占位", "tool.ws = 'ws://{$host}:8282' \n tool.api = 'http://{$host}/api' ", $douyin);
        $douyin = str_replace("//激活码code占位", "jihuoma = '$jihuoma'", $douyin);
        $douyin = str_replace("//usercode占位", "user_id = '$user_id'", $douyin);
        $douyin = str_replace("//codetime占位", "codetime='{$codetime}'", $douyin);
        $douyin = str_replace("var mytool = require('tool.js')", '', $douyin);
        $douyin = str_replace("mytool.", 'tool.', $douyin);

        return $tool . PHP_EOL . $douyin;
    }

    public function actionJs()
    {
        $jihuoma = $this->getPageParam('jihuoma');
        $user_id = $this->getPageParam('user_id');

        $android = Android::findOne([
            'jihuoma' => $jihuoma,
        ]);

        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
            'user_id' => $user_id,
        ]);
        $isvalid = $modelJiHuoMa && $modelJiHuoMa->expire > time();
        if( !$isvalid ){
            $msg = '激活码无效或已过期';
        }else{
            if($android && $android->isonline){
                $msg = '激活码正在被使用，请将使用手机重启等待1分钟后重新操作';
                $isvalid = false;
            }
        }

        if ($isvalid == false) {
            return [
                'msg' => $msg,
            ];
        }else{
            $modelJiHuoMa->had_used = 1;
            $modelJiHuoMa->save();
        }

        $code = $this->getJavascriptCode();
        $m5 = $this->getJavascriptCodeMd5();

        if($android){
            $android->can_upgrade_code = $android->upgrade_code = 0;//已是最新代码
            $android->codetime = time();
            $android->save();
        }

        return [
            'code' => $code,
            'md5' => $m5,
        ];
    }


    public function actionJihuomaInfo()
    {
        $jihuoma = $this->getPageParam('jihuoma');
        $user_id = $this->getPageParam('user_id');

        $m = Jihuoma::findOne([
           'user_id' => $user_id,
           'jihuoma' => $jihuoma,
        ]);
        $m->expire = date('Y-m-d H:i:s', $m->expire);
        return $m;
    }


    public function actionCheck()
    {
        $jihuoma = $this->getPageParam('jihuoma');
        $filemd5 = $this->getPageParam('md5');

        $m5 = $this->getJavascriptCodeMd5();

        //如果有设备，是否对不上代码的md5
        $android = Android::findOne([
            'jihuoma' => $jihuoma,
        ]);
        if($android) {
            $android->can_upgrade_code = $filemd5 != $m5 ? 1 : 0;
            $android->save();
        }

        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
        ]);
        $isvalid = $modelJiHuoMa && $modelJiHuoMa->expire > time();

        $doUpgrade = $android ? $android->upgrade_code : 0;

        //关闭后让设备代码正常升级后重新运行
        if($doUpgrade && $android){
            \Yii::$app->services->autojs->wsClose($android);
            $android->isonline = 0;
            $android->save();
        }

        return [
            'isvalid' => $isvalid,
            'md5' => $m5,
            'upgrade_code' => $doUpgrade,
        ];
    }

}