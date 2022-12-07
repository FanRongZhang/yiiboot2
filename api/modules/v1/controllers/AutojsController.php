<?php

namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;
use common\models\autojs\Android;
use common\models\jd\Sku;
use GatewayWorker\Lib\Gateway;


class AutojsController extends OnAuthController
{
    public $modelClass = Android::class;

    /**
     * @var array
     */
    protected $authOptional = ['index', 'save'];


    public function actionSave()
    {
        $m = Android::findOne([
            'jiqiid' => $this->getPageParam('jiqiid')
        ]);
        if($m == false){
            $m = new Android();
            $m->createtime = time();
            $m->label = $m->fenlei = '';
        }
        $m->setAttributes($this->pageParam, false);
        $m->isonline = 1;

        return $m->save();
    }


    public function actionClose()
    {
        $m = Android::findOne([
            'jiqiid' => $this->getPageParam('jiqiid')
        ]);
        $m->isonline = 0;

        return $m->save();
    }
}
