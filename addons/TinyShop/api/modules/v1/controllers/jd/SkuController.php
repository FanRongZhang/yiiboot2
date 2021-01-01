<?php

namespace addons\TinyShop\api\modules\v1\controllers\jd;

use addons\TinyShop\common\models\jd\Sku;
use api\controllers\OnAuthController;


class SkuController extends OnAuthController
{
    public $modelClass = Sku::class;

    /**
     * @var array
     */
    protected $authOptional = ['index', 'save'];

    //http://localhost/api/tiny-shop/v1/jd/sku/index
    public function actionIndex()
    {
        return "time is ".time();
    }

    public function actionSave()
    {
        $arySkuID = explode(',',\Yii::$app->request->get()['skus']);
        $page = \Yii::$app->request->get()['page'];

        foreach ($arySkuID as $id) {
            $m = Sku::findOne([
                'skuid' => $id,
            ]);
            if (!$m) {
                $m = new Sku();
            }
            $m->skuid = $id;
            $m->page = $page;
            $m->checktime = time();
            $m->save();
        }

        return 'ok';
    }
}
