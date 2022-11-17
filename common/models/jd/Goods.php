<?php

namespace common\models\jd;

use Yii;

/**
 * This is the model class for table "yii_goods".
 *
 * @property int $id
 * @property string $brandCode
 * @property string $brandName
 * @property string $cid1
 * @property string $cid1Name
 * @property string $cid2
 * @property string $cid2Name
 * @property string $cid3
 * @property string $cid3Name
 * @property int $comments 评论数
 * @property int $isHot
 * @property string $imageList
 * @property string $whiteImage
 * @property int $isJdSale
 * @property string $materialUrl
 * @property string $shopName
 * @property string $skuId
 * @property string $skuName
 * @property array $promotionInfoJson 优惠信息数据
 * @property int $program_last_check_time 软件上次检测数据时间
 * @property int $is_up 是否显示到平台
 * @property int $stockState 1:有货  0:无货
 * @property string $shopId
 * @property string $shopLevel
 * @property array $couInfoJson 凑着买满减的信息
 * @property string $price
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brandCode', 'brandName', 'cid1', 'cid1Name', 'cid2', 'cid2Name', 'cid3', 'cid3Name', 'comments', 'isHot', 'imageList', 'whiteImage', 'isJdSale', 'materialUrl', 'shopName', 'skuId', 'skuName', 'promotionInfoJson', 'program_last_check_time', 'is_up', 'stockState', 'shopId', 'shopLevel', 'couInfoJson'], 'required'],
            [['comments', 'isHot', 'isJdSale', 'program_last_check_time', 'is_up', 'stockState'], 'integer'],
            [['imageList'], 'string'],
            [['promotionInfoJson', 'couInfoJson'], 'safe'],
            [['shopLevel','price'], 'number'],
            [['brandCode', 'brandName', 'cid1', 'cid1Name', 'cid2', 'cid2Name', 'cid3', 'cid3Name', 'skuId', 'shopId'], 'string', 'max' => 50],
            [['whiteImage'], 'string', 'max' => 300],
            [['materialUrl', 'skuName'], 'string', 'max' => 200],
            [['shopName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brandCode' => 'Brand Code',
            'brandName' => 'Brand Name',
            'cid1' => 'Cid1',
            'cid1Name' => 'Cid1name',
            'cid2' => 'Cid2',
            'cid2Name' => 'Cid2name',
            'cid3' => 'Cid3',
            'cid3Name' => 'Cid3name',
            'comments' => 'Comments',
            'isHot' => 'Is Hot',
            'imageList' => 'Image List',
            'whiteImage' => 'White Image',
            'isJdSale' => 'Is Jd Sale',
            'materialUrl' => 'Material Url',
            'shopName' => 'Shop Name',
            'skuId' => 'Sku ID',
            'skuName' => 'Sku Name',
            'promotionInfoJson' => 'Promotion Info Json',
            'program_last_check_time' => 'Program Last Check Time',
            'is_up' => 'Is Up',
            'stockState' => 'Stock State',
            'shopId' => 'Shop ID',
            'shopLevel' => 'Shop Level',
            'couInfoJson' => 'Cou Info Json',
            'price' => 'Price',
        ];
    }
}
