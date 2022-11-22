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
        $ary = Yii::$app->services->jd->getItemInfo('67938515689',false);
        var_dump($ary);
    }
}