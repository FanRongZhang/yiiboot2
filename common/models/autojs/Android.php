<?php

namespace common\models\autojs;

use Yii;

/**
 * This is the model class for table "yii_android".
 *
 * @property int $id
 * @property string $jihuoma
 * @property int $w
 * @property int $h
 * @property string $brand
 * @property string $product
 * @property string $release
 * @property string $android_id
 * @property string $client_id
 * @property string $fenlei
 * @property string $label
 * @property int $isonline
 * @property string $keyword
 * @property string $action
 * @property int $createtime
 * @property int $merchant_id
 */
class Android extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_android';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jihuoma', 'w', 'h', 'brand', 'product', 'release', 'isonline', 'createtime', 'merchant_id'], 'required'],
            [['w', 'h', 'isonline', 'createtime', 'merchant_id'], 'integer'],
            [['jihuoma', 'brand', 'product', 'release', 'android_id', 'client_id', 'fenlei', 'label', 'keyword', 'action'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jihuoma' => 'Jihuoma',
            'w' => 'W',
            'h' => 'H',
            'brand' => 'Brand',
            'product' => 'Product',
            'release' => 'Release',
            'android_id' => 'Android ID',
            'client_id' => 'Client ID',
            'fenlei' => 'Fenlei',
            'label' => 'Label',
            'isonline' => 'Isonline',
            'keyword' => 'Keyword',
            'action' => 'Action',
            'createtime' => 'Createtime',
            'merchant_id' => 'Merchant ID',
        ];
    }
}
