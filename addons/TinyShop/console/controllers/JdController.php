<?php

namespace addons\TinyShop\console\controllers;

use addons\TinyShop\common\models\jd\Goods;
use addons\TinyShop\common\models\jd\Sku;
use addons\TinyShop\common\models\product\Brand;
use addons\TinyShop\common\models\product\Cate;
use addons\TinyShop\merchant\forms\ProductForm;
use yii\console\Controller;
use yii\web\NotFoundHttpException;


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

            $model = $this->findFormModel($one->skuId);
            $model->id = $one->skuId;
            $model->tags = $one->fxgServiceList;
            $model->covers = \Qiniu\json_decode($one->imageList, true);
            $model->name = $one->skuName;
            $model->picture = $one->whiteImage;
            $model->cate_id = $one->cid3;
            $model->brand_id = $b->id;
            $model->type_id = '';
            $model->sketch = '';
            $model->intro = '';
            $model->keywords = '';
//            $model->tags = '';
            $model->marque = '';
            $model->barcode = '';
            $model->sales = rand(10000, 30000);
            $model->price = $one->price;
            $model->market_price = '';
            $model->cost_price = '';
            /**
             * [
             * 'man' => $man,//满
             * 'jian' => $jian,//减
             * 'danshu' => $danshu,//几单达到满
             * 'meidansheng' => $meiDanJian,//每单省
             * ];
             */
            $model->is_open_wholesale = 1;
            $model->wholesale_people = $one->couInfoJson[0]['danshu'];
            $model->wholesale_price = bcsub($one->price, $one->couInfoJson[0]['meidansheng'], 2);
            $model->stock = 20000;
            $model->warning_stock = 100;
//            $model->covers ='';
            $model->posters = '';
            $model->state = 1;
            $model->sort = $model->is_package = $model->is_attribute = 0;
            $model->product_status = 1;
            $model->shipping_type = 2;
            $model->shipping_fee = 10;
            $model->marketing_type = 0;
            $model->is_open_commission = $model->is_open_presell = $model->is_virtual = $model->is_bill = $model->min_buy = $model->max_buy = 0;

            // 开启事务
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                // 载入数据并验证
                $model->skuData = [];
                $model->attributeValueData = [];
                $model->specValueData = [];
                $model->specValueFieldData = [];
                !empty($model->covers) && $model->covers = serialize($model->covers);
                !empty($model->tags) && $model->tags = implode(',', $model->tags);
                if (!$model->save()) {
                    throw new NotFoundHttpException(json_encode($model->firstErrors));
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();

                echo $e->getMessage();
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