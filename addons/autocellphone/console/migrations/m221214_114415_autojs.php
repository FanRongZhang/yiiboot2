<?php

use yii\db\Migration;

/**
 * Class m221214_114415_autojs
 */
class m221214_114415_autojs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$sql = <<<EOF
CREATE TABLE {{%autojs_android}} (
  `id` int NOT NULL AUTO_INCREMENT,
  `jihuoma` varchar(200) NOT NULL,
  `w` int NOT NULL,
  `h` int NOT NULL,
  `brand` varchar(200) NOT NULL,
  `product` varchar(200) NOT NULL,
  `release` varchar(200) NOT NULL,
  `android_id` varchar(200) DEFAULT NULL,
  `client_id` varchar(200) DEFAULT NULL,
  `fenlei` varchar(200) DEFAULT NULL,
  `label` varchar(200) DEFAULT NULL,
  `isonline` int NOT NULL,
  `keyword` varchar(200) DEFAULT NULL,
  `action` varchar(200) DEFAULT NULL,
  `createtime` int NOT NULL,
  `user_id` int NOT NULL,
  `can_upgrade_code` int NOT NULL DEFAULT '0' COMMENT '可以升级代码',
  `upgrade_code` int NOT NULL DEFAULT '0' COMMENT '是否升级代码',
  `codetime` int NOT NULL DEFAULT '0' COMMENT '最近一次获取代码时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB   COMMENT='安卓机器列表';

CREATE TABLE {{%autojs_douyinqun}} (
  `id` int NOT NULL AUTO_INCREMENT,
  `douyinhao` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `mingcheng` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `renshu` int NOT NULL,
  `menkan` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `isjoined` int NOT NULL,
  `createtime` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE {{%autojs_douyinuser}} (
  `id` int NOT NULL AUTO_INCREMENT,
  `dyh` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `nick` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `huozan` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `guanzhu` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `fensi` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `zuopin` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `fensiqun` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `xihuan` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `note` varchar(350) COLLATE utf8mb4_bin DEFAULT NULL,
  `createtime` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

CREATE TABLE {{%autojs_jihuoma}} (
  `id` int NOT NULL AUTO_INCREMENT,
  `jihuoma` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `merchant_id` int NOT NULL DEFAULT '0' COMMENT '生产激活码的商户',
  `expire` int NOT NULL,
  `had_used` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0' COMMENT '最终分配到用户',
  `usedtime` int NOT NULL DEFAULT '0',
  `createtime` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB ;

EOF;
$this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221214_114415_autojs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221214_114415_autojs cannot be reverted.\n";

        return false;
    }
    */
}
