<?php

namespace addons\TinyShop\api\modules\v1\controllers\jd;

use addons\TinyShop\common\models\jd\Sku;
use api\controllers\OnAuthController;


class AccountController extends OnAuthController
{
    public $modelClass = Sku::class;

    /**
     * @var array
     */
    protected $authOptional = ['*'];

    //http://localhost/api/tiny-shop/v1/jd/sku/index
    public function actionIndex()
    {
        return "time is ".time();
    }

    //通知扫码登录京东
    public function actionLoginNotify()
    {
        echo "通知扫码登录京东";
        if(\Yii::$app->request->get()['name'] == 'zrf'){
            //扫码二维码
            $url = \Yii::$app->request->getHostName() . '/jdcrawler/login_qr.png?r='.bin2hex(random_bytes(10));

        }

        return 'ok';
    }
}
