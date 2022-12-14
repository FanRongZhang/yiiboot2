<?php

use yii\db\Migration;

/**
 * Class m221214_123754_addon_shop_jd_v1
 * ./yii migrate --migrationPath=@addons/TinyShop/console/migrations_v2

 */
class m221214_123754_addon_shop_jd_v1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$sql=<<<EOF

CREATE TABLE {{%addon_shop_jd_goods}} (
  `id` int NOT NULL AUTO_INCREMENT,
  `brandCode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `brandName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cid1` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cid1Name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cid2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cid2Name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cid3` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `cid3Name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `comments` int NOT NULL COMMENT '评论数',
  `isHot` int NOT NULL,
  `imageList` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `whiteImage` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `isJdSale` int NOT NULL,
  `materialUrl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `shopName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `skuId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `skuName` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `promotionInfoJson` json NOT NULL COMMENT '优惠信息数据',
  `program_last_check_time` int NOT NULL COMMENT '软件上次检测数据时间',
  `isUp` int NOT NULL COMMENT '是否显示到平台',
  `stockState` int NOT NULL COMMENT '1:有货  0:无货',
  `shopId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `shopLevel` decimal(10,2) NOT NULL,
  `couInfoJson` json NOT NULL COMMENT '凑着买满减的信息',
  `price` decimal(10,2) NOT NULL,
  `maxMeiDanSheng` decimal(10,2) NOT NULL,
  `endTime` int NOT NULL DEFAULT '0' COMMENT '满减结束时间',
  `fxg` int DEFAULT NULL COMMENT '放心购',
  `is7ToReturn` int DEFAULT NULL COMMENT '支持7天无理由退货',
  `fxgServiceList` json DEFAULT NULL COMMENT '服务支持',
  `isCanGetInfoFromZTK` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE {{%addon_shop_jd_sku}} (
  `skuid` varchar(50) NOT NULL,
  `page` int NOT NULL,
  `url` varchar(200) NOT NULL,
  `checktime` int NOT NULL,
  PRIMARY KEY (`skuid`)
);
EOF;

$this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221214_123754_addon_shop_jd_v1 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221214_123754_addon_shop_jd_v1 cannot be reverted.\n";

        return false;
    }
    */
}
