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
        $ary = Yii::$app->services->jd->getItemInfo('18241105903');
        var_dump($ary);
    }
}