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

        Yii::$app->services->autojs->makeJiHuoma(10,time()+24*3600*60,2);

    }
}