<?php

namespace api\controllers;

use GatewayWorker\Lib\Gateway;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class WsController extends Controller
{
    /**
     * @return string
     */
    public function actionSend()
    {
        Gateway::sendToAll(json_encode([
            'msg' => $_GET['msg'],
        ]));
        return "ok";
    }
}
