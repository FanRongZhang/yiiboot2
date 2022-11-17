<?php

namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;
use common\models\jd\Sku;


class SkuController extends OnAuthController
{
    public $modelClass = Sku::class;

    /**
     * @var array
     */
    protected $authOptional = ['index', 'save'];


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
