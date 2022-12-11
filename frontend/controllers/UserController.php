<?php

namespace frontend\controllers;

use common\helpers\Url;
use common\models\autojs\Android;
use common\models\autojs\Douyinqun;
use common\models\autojs\Douyinuser;
use common\models\autojs\Jihuoma;
use common\models\base\SearchModel;
use GatewayWorker\Lib\Gateway;
use Yii;
use yii\web\HtmlResponseFormatter;
use yii\web\Response;


class UserController extends BaseController
{
    /**
     * 关闭csrf
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    public $needLogin = true;

    /**
     * @var Android
     */
    public $modelClass = Android::class;


    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->post('hasEditable')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $post = Yii::$app->request->post();

            $model = $this->modelClass::findOne($post['editableKey']);

            $model->setAttributes($post[$model->formName()][$post['editableIndex']], false);
            $out = ['output' => '', 'message' => ''];

            if ($model->isAttributeChanged('keyword')) {
                $model->action = "search";
            }
            if ($output = $model->save()) {
                Gateway::sendToClient($model->client_id, json_encode([
                    'action' => $model->action,
                    'keyword' => $model->keyword,
                ]));
                $out = ['output' => $output, 'message' => '成功'];
            } else {
                $out['message'] = json_encode($model->getErrors());
            }
            return $out;
        }


        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [
                //'package_name','link_name',
            ], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['user_id' => Yii::$app->user->identity->getId()]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }


    public function actionSendAction()
    {
        Yii::$app->response->format = Yii::$app->response::FORMAT_JSON;

        $all = Android::find()->andWhere([
            'id' => $this->getPageParam('ids'),
            'user_id' => Yii::$app->user->identity->getId(),
        ])->all();

        $action = $this->getPageParam('action');

        /** @var Android $one */
        foreach ($all as $one) {
            if($action == 'search' && $one->keyword){ // 搜索
                $one->action = $action;
                $one->save();
                Gateway::sendToClient($one->client_id, json_encode([
                    'action' => $one->action,
                    'keyword' => $one->keyword,
                ]));
            }elseif($action == 'doagain'){//重复上一个操作

                //有些需要关键字等额外条件
                if( in_array($one->action,['search'])  && $one->keyword) {
                    Gateway::sendToClient($one->client_id, json_encode([
                        'action' => $one->action,
                        'keyword' => $one->keyword,
                    ]));
                }else{
                    Gateway::sendToClient($one->client_id, json_encode([
                        'action' => $one->action,
                        'keyword' => $one->keyword,
                    ]));
                }

            }elseif($action == 'upgrade' && $one->can_upgrade_code){ //代码升级
                $one->action = $action;
                $one->upgrade_code = 1;
                $one->save();
            }elseif($action == 'checkonline'){ //查在线
                $one->isonline = \Yii::$app->services->autojs->isJihuomaOnline($one);
                $one->save();
            }elseif($action == 'shutdown'){ //shut down
                Gateway::sendToClient($one->client_id, json_encode([
                    'action' => $action,
                ]));
                $one->isonline = 0;
                $one->save();
                Yii::$app->services->autojs->wsClose($one);
            }else{ //发指令
                $one->action = $action;
                $one->save();
                Gateway::sendToClient($one->client_id, json_encode([
                    'action' => $one->action,
                ]));
            }
        }
        return '';//
    }

    public function actionUser()
    {
        $searchModel = new SearchModel([
            'model' => Douyinuser::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [
                //'package_name','link_name',
            ], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['user_id' => Yii::$app->user->identity->getId()]);

        return $this->render('user', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }


    public function actionQun()
    {
        $searchModel = new SearchModel([
            'model' => Douyinqun::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [
                //'package_name','link_name',
            ], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['user_id' => Yii::$app->user->identity->getId()]);

        return $this->render('qun', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

    public function actionJihuoma()
    {
        $searchModel = new SearchModel([
            'model' => Jihuoma::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [
                //'package_name','link_name',
            ], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['user_id' => Yii::$app->user->identity->getId()]);

        return $this->render('jihuoma', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }



}