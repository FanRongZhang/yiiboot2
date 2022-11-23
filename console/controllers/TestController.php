<?php

namespace console\controllers;

use common\helpers\StringHelper;
use common\models\jd\Goods;
use common\queues\PackageSearchJob;
use common\traits\QueueTrait;
use Yii;
use yii\console\Controller;

/**
 *
 * 首单 免服务费
 *
 * Class TestController
 * @package console\controllers
 */
class TestController extends Controller
{
    public function actionIndex(){
        //$ary = Yii::$app->services->jd->getInfoViaZTK('67938515689');
//        $ary = Yii::$app->services->jd->getItemInfo('67938515689',false);
        $ary = Yii::$app->services->jd->saveAfterGetItemInfo('67938515689');
        var_dump($ary);
    }
}