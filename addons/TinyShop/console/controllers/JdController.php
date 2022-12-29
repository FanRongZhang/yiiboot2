<?php

namespace addons\TinyShop\console\controllers;

use addons\TinyShop\common\models\forms\ProductSearch;
use addons\TinyShop\common\models\jd\Goods;
use addons\TinyShop\common\models\jd\Sku;
use addons\TinyShop\common\models\product\Brand;
use addons\TinyShop\common\models\product\Cate;
use addons\TinyShop\merchant\forms\ProductForm;
use yii\console\Controller;


class JdController extends Controller
{
    /**
     * ./yii tiny-shop/jd/cat
     */
    public function actionCat()
    {
        $aryAll = Goods::find()
            ->select('cid1,cid1name,cid2,cid2name,cid3,cid3name')
            ->andWhere('isUp=1')
            ->distinct()->asArray()->all();
        foreach ($aryAll as $one){
            $m1 = Cate::findOne([
                'id' => $one['cid1'],
            ]);
            if($m1 == false){
                $m1 = new Cate([
                    'id' => $one['cid1'],
                    'title' => $one['cid1name'],
                    'pid' => 0,
                ]);
            }

            //看分类下数量
            $model = new ProductSearch();
            $model->cate_id = $m1->id;
            $aryM = \Yii::$app->tinyShopService->product->getListBySearch($model);
            if(!$aryM || count($aryM) < 5){
                $m1->index_block_status = 0;
            }else{
                $m1->index_block_status = 1;
            }
            $m1->save();


            $m2 = Cate::findOne([
                'id' => $one['cid2'],
            ]);
            if($m2 == false){
                $m2 = new Cate([
                    'id' => $one['cid2'],
                    'title' => $one['cid2name'],
                    'pid' => $m1->id,
                ]);
            }
            $m1->index_block_status = 0;
            $m2->save();


            $m3 = Cate::findOne([
                'id' => $one['cid3'],
            ]);
            if($m3 == false){
                $m3 = new Cate([
                    'id' => $one['cid3'],
                    'title' => $one['cid3name'],
                    'pid' => $m2->id,
                ]);
            }
            $m3->save();

        }
    }


    public function actionPull(){
        foreach (Sku::find()->all() as $one){
            \Yii::$app->tinyShopService->jd->saveAfterGetItemInfo($one->skuid);
        }
    }


    public function actionSync()
    {
        $this->actionCat();

        //下架不合适产品
        ProductForm::updateAll([
            'product_status' => 0,
        ],[
           'id' =>  Goods::find()->select('id')->andWhere('isUp=0'),
        ]);

        $aryAll = Goods::find()
            ->andWhere('isUp=1')
            ->all();
        /** @var Goods $one */
        foreach ($aryAll as $one) {
            $b = Brand::findOne([
                'id' => $one->brandCode,
                'title' => $one->brandName,
            ]);
            if ($b == false) {
                $b = new Brand([
                    'id' => $one->brandCode,
                    'title' => $one->brandName,
                    'merchant_id' => 0,
                    'cate_id' => $one->cid3,
                ]);
                $b->save();
            }

            $model = $this->findFormModel($one->id);
            $model->id = $one->id;
            $model->end_time = $one->endTime;
            $model->merchant_id = 0;
            $model->tags = $one->fxgServiceList ? implode(',',$one->fxgServiceList) : '';
            $_ary = \Qiniu\json_decode($one->imageList, true);
            $_intro = '';
            foreach ($_ary as $_img){
                $_intro .= "<img src='{$_img['url']}' style='width:100%;border:0;'/>";
            }
            $model->intro = $_intro;
            $model->name = $one->skuName;
            $model->jd_sku_id = $one->skuId;
            $model->shop_name = $one->shopName;
            $model->picture = $one->whiteImage;
            $model->covers = [$one->whiteImage];
            $model->cate_id = $one->cid3;
            $model->brand_id = $b->id;
            $model->type_id = '';
            $model->sketch = '';
            $model->keywords = $one->skuName;
//            $model->tags = '';
            $model->marque = '';
            $model->barcode = '';
            $model->sales = rand(10000, 30000);
            $model->market_price = $one->price;
            /**
             * [
             * 'man' => $man,//满
             * 'jian' => $jian,//减
             * 'danshu' => $danshu,//几单达到满
             * 'meidansheng' => $meiDanJian,//每单省
             * ];
             */
            $model->is_open_wholesale = 1;
            $model->couInfoJson = json_encode($one->couInfoJson,JSON_UNESCAPED_UNICODE);
            $model->wholesale_people = $one->couInfoJson[0]['danshu'];
            $model->meidansheng = $one->couInfoJson[0]['meidansheng'];
            $model->price = $model->cost_price = $model->wholesale_price = bcsub($one->price, $one->couInfoJson[0]['meidansheng'], 2);
            if($model->isNewRecord) {
                $model->stock = rand(15000, 50000);
                $model->warning_stock = 100;
            }
//            $model->covers ='';
            $model->posters = '[]';
            $model->state = 1;
            $model->sort = 0;
            $model->is_package = $model->is_attribute = '0';
            $model->product_status = 1;
            $model->shipping_type = 2;
            $model->shipping_fee = 10;
            $model->shipping_fee_id = 1;
            $model->shipping_fee_type = 1;
            $model->product_weight = 1;
            $model->marketing_type = '0';
            $model->is_open_commission = $model->is_open_presell = $model->is_virtual = $model->is_bill =0;
            $model->supplier_id = $one->isJdSale ? 1 : 0;
            $model->comment_num = $one->comments;
            $model->min_buy = $model->max_buy = 1;

            //省不到这个金额就下架
            if($model->meidansheng < 20){
                $model->product_status = 0;
            }

            // 开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                // 载入数据并验证
                $model->skuData = [];
                $model->attributeValueData = [];
                $model->specValueData = [];
                $model->specValueFieldData = [];
                !empty($model->covers) && $model->covers = serialize($model->covers);
                if (!$model->save()) {
                    throw new \Exception(json_encode($model->errors));
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                echo $e->getMessage()  . "\n";
//                var_dump('model is ', $model->toArray());
//                exit;
            }

        }
    }


    /**
     * 返回模型
     *
     * @param $id
     * @return ProductForm|array|\yii\db\ActiveRecord|null
     */
    protected function findFormModel($id)
    {
        if (empty($id) || empty(($model = ProductForm::find()->where(['id' => $id])->one()))) {
            $model = new ProductForm();
            $model->merchant_id = 0;
            $model->loadDefaultValues();
        }

        return $model;
    }

}