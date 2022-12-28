<?php

use yii\db\Migration;

/**
 * Class m221225_073234_alter_addon_shop_product
 */
class m221225_073234_alter_addon_shop_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //后续新增

        $this->addColumn('{{%addon_shop_product}}','end_time','int default 0 comment "满减结束时间"');
        $this->addColumn('{{%addon_shop_product}}','meidansheng','decimal(10,2) default 0 comment "最少单数每单省多少钱"');
        $this->addColumn('{{%addon_shop_product}}','shop_name','varchar(100) null comment "店铺名称"');
        $this->addColumn('{{%addon_shop_product}}','couInfoJson','varchar(300) null comment "满减json信息"');
        $this->addColumn('{{%addon_shop_product}}','jd_sku_id','varchar(50) null comment "jd sku"');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221225_073234_alter_addon_shop_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221225_073234_alter_addon_shop_product cannot be reverted.\n";

        return false;
    }
    */
}
