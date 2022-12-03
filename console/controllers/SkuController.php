<?php

namespace console\controllers;

use common\helpers\StringHelper;
use common\models\jd\Sku;
use common\queues\PackageSearchJob;
use common\traits\QueueTrait;
use Yii;
use yii\console\Controller;


class SkuController extends Controller
{
    public function actionIndex(){
        $arySku = Sku::find()->orderBy('page,checktime')->all();
        $i = 0;
        /** @var Sku $oneSku */
        foreach ($arySku as $oneSku){
            echo "第 $i 个商品" .$oneSku->skuid.PHP_EOL;
            try {
                $goods = Yii::$app->services->jd->saveAfterGetItemInfo($oneSku->skuid);
                if(!$goods) {
                    ##selunim 进行京东商品详情的获取

                }
                $oneSku->checktime = time();
                $oneSku->save();
                sleep(rand(7,15));
            }catch (\Throwable $e){
                echo $e->getMessage() . PHP_EOL
                    . 'line:' . $e->getLine() . PHP_EOL
                ;
                break;
            }
        }
    }
}


/*
 *
 CREATE TABLE `yii_goods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `brandCode` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `brandName` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `cid1` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `cid1Name` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `cid2` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `cid2Name` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `cid3` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `cid3Name` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `comments` int NOT NULL COMMENT '评论数',
  `isHot` int NOT NULL,
  `imageList` text COLLATE utf8mb4_bin NOT NULL,
  `whiteImage` varchar(300) COLLATE utf8mb4_bin NOT NULL,
  `isJdSale` int NOT NULL,
  `materialUrl` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `shopName` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `skuId` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `skuName` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `promotionInfoJson` json NOT NULL COMMENT '优惠信息数据',
  `program_last_check_time` int NOT NULL COMMENT '软件上次检测数据时间',
  `is_up` int NOT NULL COMMENT '是否显示到平台',
  `stockState` int NOT NULL COMMENT '1:有货  0:无货',
  `shopId` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `shopLevel` decimal(10,2) NOT NULL,
  `couInfoJson` json NOT NULL COMMENT '凑着买满减的信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
 *
 * */