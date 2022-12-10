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

    public function actionIsvalid(){
        $jihuoma = $this->getPageParam('jihuoma');

        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
        ]);
        return $modelJiHuoMa && $modelJiHuoMa->had_used == 0 && $modelJiHuoMa->expire > time();
    }

    private function getJavascriptCode(){
        $host = \Yii::$app->request->getHostName();

        $jihuoma = $this->getPageParam('jihuoma');

        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
        ]);
        $user_id = $modelJiHuoMa?$modelJiHuoMa->user_id:'0';

        $tool = file_get_contents(\Yii::getAlias("@root/web/autojs/tool.js"));
        $tool = str_replace("module.exports = tool",'', $tool);

        $douyin = file_get_contents(\Yii::getAlias("@root/web/autojs/douyin_v6.js"));

        $douyin = str_replace("//toolcode占位","tool.ws = 'ws://{$host}:8282' \n tool.api = 'http://{$host}/api' ", $douyin);
        $douyin = str_replace("//激活码code占位","jihuoma = '$jihuoma'", $douyin);
        $douyin = str_replace("//usercode占位","user_id = '$user_id'", $douyin);
        $douyin = str_replace("//版本code占位","JSVERSION='221210'", $douyin);
        $douyin = str_replace("var mytool = require('tool.js')",'', $douyin);
        $douyin = str_replace("mytool.",'tool.', $douyin);

        return $tool . PHP_EOL . $douyin ;
    }

    public function actionJs()
    {
        echo $this->getJavascriptCode();
        exit;
    }

    public function actionJihuoma(){
        $jihuoma = $this->getPageParam('jihuoma');
        $modelJiHuoMa = Jihuoma::findOne([
            'jihuoma' => $jihuoma,
        ]);
        return [
            'isvalid' => $modelJiHuoMa ? $modelJiHuoMa->expire > time() : false,
            'md5' => md5($this->getJavascriptCode()),
        ];
    }
}