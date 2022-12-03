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
//        $ary = Yii::$app->services->jd->getInfoViaZTK('10040713168242');
//        $ary = Yii::$app->services->jd->getItemInfo('67938515689',true);
//        $ary = Yii::$app->services->jd->saveAfterGetItemInfo('67938515689');
//        var_dump($ary);

        $ary = file_get_contents("http://api-gw.haojingke.com/index.php/v1/api/jd/goodsdetail?apikey=1f72908997f3c187&goods_id=10040713168242");
        echo "\n" .($ary) . "\n";
//        echo date('y-m-d',1668355200);

    }
}