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
use linslin\yii2\curl\Curl;
use common\helpers\ResultHelper;
use common\models\common\Attachment;
use yii\web\Response;
use Qiniu\Auth;


class UserController extends BaseController
{
    /**
     * 关闭csrf
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    public $needLogin = false;

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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }


    public function actionSendAction()
    {
        $all = Android::find()->andWhere([
            'id' => $this->getPageParam('ids')
        ])->all();
        /** @var Android $one */
        foreach ($all as $one) {
            $one->action = $this->getPageParam('action');
            $one->save();
            if($one->action == 'search' && $one->keyword){
                Gateway::sendToClient($one->client_id, json_encode([
                    'action' => $one->action,
                    'keyword' => $one->keyword,
                ]));
            }elseif($one->action == 'restart' && $one->can_upgrade_code){
                Gateway::sendToClient($one->client_id, json_encode([
                    'action' => $one->action,
                    'keyword' => $one->keyword,
                ]));
            }else{
                Gateway::sendToClient($one->client_id, json_encode([
                    'action' => $one->action,
                ]));
            }
        }
        return '';
    }

    public function actionReset()
    {
        $all = Android::find()->andWhere([
            'id' => $this->getPageParam('ids')
        ])->all();
        foreach ($all as $one) {

            Gateway::sendToClient($one->client_id, json_encode([
                'action' => $one->action,
                'keyword' => $one->keyword
            ]));
        }
        return $this->redirect($this->message("操作成功", Url::to(['index'])));
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

        return $this->render('jihuoma', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }



}