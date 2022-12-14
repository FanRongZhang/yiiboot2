<?php

namespace addons\TinyShop\common\models\jd;

use Yii;

/**
 * This is the model class for table "yii_sku".
 *
 * @property string $skuid
 * @property int $page
 * @property string $url
 * @property int $checktime
 */
class Sku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_addon_shop_jd_sku';
    }

}
