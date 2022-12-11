<?php


namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;
use common\models\autojs\Android;
use common\models\autojs\Douyinqun;
use common\models\autojs\Douyinuser;
use common\models\autojs\Jihuoma;
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

    public function actionIsvalid()
    {
        $jihuoma = $this->getPageParam('jihuoma');

        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
        ]);
        return $modelJiHuoMa && $modelJiHuoMa->had_used == 0 && $modelJiHuoMa->expire > time();
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

        $tool = file_get_contents(\Yii::getAlias("@root/web/autojs/tool.js"));
        $tool = str_replace("module.exports = tool", '', $tool);

        $douyin = file_get_contents(\Yii::getAlias("@root/web/autojs/douyin_v6.js"));

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
        if ($this->actionIsvalid() == false) {
            return [
                'code' => "toast('激活码无效或已使用或已过期') \n print('激活码无效或已使用或已过期') \n",
                'md5' => '',
            ];
        }

        $code = $this->getJavascriptCode();
        $m5 = md5($code);

        $a = Android::findOne([
            'jihuoma' => $jihuoma,
        ]);
        $a->codetime = time();
        $a->can_upgrade_code = 0;
        $a->save();

        return [
            'code' => $code,
            'md5' => $m5,
        ];
    }

    public function actionCheck()
    {
        $jihuoma = $this->getPageParam('jihuoma');
        $filemd5 = $this->getPageParam('md5');

        $a = Android::findOne([
            'jihuoma' => $jihuoma,
        ]);
        $code = $this->getJavascriptCode();
        $m5 = md5($code);

        $a->can_upgrade_code = (int)$filemd5 != $m5;
        $a->save();

        return [
            'isvalid' => $this->actionIsvalid(),
            'md5' => $m5,
        ];
    }
}