<?php

namespace addons\autocellphone\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\autocellphone\frontend\controllers
 */
class DefaultController extends BaseController
{
    /**
    * 首页
    *
    * @return string
    */
    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }
}