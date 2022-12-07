<?php

namespace common\models\autojs;

use Yii;

/**
 * This is the model class for table "yii_android".
 *
 * @property int $id
 * @property string $jiqiid
 * @property string $w
 * @property string $h
 * @property string $brand
 * @property string $product
 * @property string $release
 * @property string $imei
 * @property int $isonline
 * @property int $createtime
 * @property string $fenlei
 * @property string $label
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
            [['jiqiid', 'w', 'h', 'brand', 'product', 'release', 'imei', 'isonline', 'createtime', 'fenlei', 'label'], 'required'],
            [['isonline', 'createtime'], 'integer'],
            [['jiqiid', 'w', 'h', 'brand', 'product', 'release', 'imei', 'fenlei', 'label'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jiqiid' => 'Jiqiid',
            'w' => 'W',
            'h' => 'H',
            'brand' => 'Brand',
            'product' => 'Product',
            'release' => 'Release',
            'imei' => 'Imei',
            'isonline' => 'Isonline',
            'createtime' => 'Createtime',
            'fenlei' => 'Fenlei',
            'label' => 'Label',
        ];
    }
}
