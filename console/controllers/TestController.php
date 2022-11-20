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
        $ary = Yii::$app->services->jd->getItemInfo('66941229212');
        echo json_encode($ary);
    }
}