<?php

namespace console\controllers;

use common\helpers\StringHelper;
use common\queues\PackageSearchJob;
use common\traits\QueueTrait;
use Yii;
use yii\console\Controller;


class TestController extends Controller
{
    public function actionIndex(){
//        $ary = Yii::$app->services->jd->getItemInfo('2990352');
//        var_dump($ary);
        preg_match('/^满([\d+]*)\D+([\d+]*)/','满199元b100元', $matches);
var_dump($matches);
    }
}