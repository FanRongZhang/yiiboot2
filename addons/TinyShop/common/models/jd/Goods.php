<?php

namespace addons\TinyShop\common\models\jd;

use Yii;

/**
 * This is the model class for table "yii_addon_shop_jd_goods".
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
 * @property int $isUp 是否显示到平台
 * @property int $stockState 1:有货  0:无货
 * @property string $shopId
 * @property string $shopLevel
 * @property array $couInfoJson 凑着买满减的信息
 * @property string $price
 * @property string $maxMeiDanSheng
 * @property int $endTime 满减结束时间
 * @property int $isFxg 放心购
 * @property int $is7ToReturn 支持7天无理由退货
 * @property array $fxgServiceList 服务支持
 * @property int $isCanGetInfoFromZTK 是否可以通过接口获取到数据
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_addon_shop_jd_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brandCode', 'brandName', 'cid1', 'cid1Name', 'cid2', 'cid2Name', 'cid3', 'cid3Name', 'comments', 'isHot', 'imageList', 'whiteImage', 'isJdSale', 'materialUrl', 'shopName', 'skuId', 'skuName', 'promotionInfoJson', 'program_last_check_time', 'isUp', 'stockState', 'shopId', 'shopLevel', 'couInfoJson', 'price', 'maxMeiDanSheng'], 'required'],
            [['comments', 'isHot', 'isJdSale', 'program_last_check_time', 'isUp', 'stockState', 'endTime', 'isFxg', 'is7ToReturn', 'isCanGetInfoFromZTK'], 'integer'],
            [['imageList'], 'string'],
            [['promotionInfoJson', 'couInfoJson', 'fxgServiceList'], 'safe'],
            [['shopLevel', 'price', 'maxMeiDanSheng'], 'number'],
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
            'comments' => '评论数',
            'isHot' => 'Is Hot',
            'imageList' => 'Image List',
            'whiteImage' => 'White Image',
            'isJdSale' => 'Is Jd Sale',
            'materialUrl' => 'Material Url',
            'shopName' => 'Shop Name',
            'skuId' => 'Sku ID',
            'skuName' => 'Sku Name',
            'promotionInfoJson' => '优惠信息数据',
            'program_last_check_time' => '软件上次检测数据时间',
            'isUp' => '是否显示到平台',
            'stockState' => '1:有货  0:无货',
            'shopId' => 'Shop ID',
            'shopLevel' => 'Shop Level',
            'couInfoJson' => '凑着买满减的信息',
            'price' => 'Price',
            'maxMeiDanSheng' => 'Max Mei Dan Sheng',
            'endTime' => '满减结束时间',
            'isFxg' => '放心购',
            'is7ToReturn' => '支持7天无理由退货',
            'fxgServiceList' => '服务支持',
            'isCanGetInfoFromZTK' => '是否可以通过接口获取到数据',
        ];
    }
}
