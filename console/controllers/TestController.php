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

/*

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
  `comments` int NOT NULL,
  `imageList` text COLLATE utf8mb4_bin NOT NULL,
  `whiteImage` varchar(300) COLLATE utf8mb4_bin NOT NULL,
  `isJdSale` int NOT NULL,
  `materialUrl` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `shopId` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `skuId` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `skuName` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `promotionInfoJson` text COLLATE utf8mb4_bin NOT NULL COMMENT '优惠信息数据',
  `program_last_check_time` int NOT NULL COMMENT '软件上次检测数据时间',
  `is_up` int NOT NULL,
  `meiDanJian` decimal(10,2) NOT NULL COMMENT '满减后，每单从单价比较上看减了多少',
  `danShu` int NOT NULL COMMENT '达到满减条件需要几单（假设每单1件）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


 * */