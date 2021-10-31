<?php

namespace addons\TinyShop\common\models\common;

/**
 * Class Transmit
 * @package addons\TinyShop\common\models\common
 * @author 小主科技 <1458015476@qq.com>
 */
class Transmit extends Follow
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_shop_common_transmit}}';
    }
}