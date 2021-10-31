<?php

namespace addons\RfDevTool\backend\controllers;

/**
 * Class PhpInfoController
 * @package addons\RfDevTool\backend\controllers
 * @author 小主科技 <1458015476@qq.com>
 */
class PhpInfoController extends BaseController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }
}