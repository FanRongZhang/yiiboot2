<?php

namespace backend\controllers;

use Yii;
use common\models\autojs\Jihuoma;
use common\traits\Curd;
use common\models\base\SearchModel;

/**
* Jihuoma
*
* Class JihuomaController
* @package backend\controllers
*/
class JihuomaController extends BaseController
{
    use Curd;

    /**
    * @var Jihuoma
    */
    public $modelClass = Jihuoma::class;

    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionMake(){
        Yii::$app->services->autojs->makeJiHuoma(100,time()+3600*24*30);
    }

}
