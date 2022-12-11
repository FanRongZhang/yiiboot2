<?php

namespace api\modules\v1\controllers;

use api\controllers\OnAuthController;
use common\models\autojs\Android;
use common\models\autojs\Douyinqun;
use common\models\autojs\Douyinuser;
use common\models\autojs\Jihuoma;
use common\models\jd\Sku;
use GatewayWorker\Lib\Gateway;
use yii\db\Exception;


class AutojsController extends OnAuthController
{
    public $modelClass = Android::class;

    /**
     * @var array
     */
    protected $authOptional = ['*'];

    public function actionOnline()
    {
        //激活码被使用
        $jihuoma = Jihuoma::findOne([
            'jihuoma' => $this->getPageParam('jihuoma'),
            'user_id' => $this->getPageParam('user_id'),
        ]);
        if($jihuoma){
            $jihuoma->had_used = 1;
            $jihuoma->usedtime = time();
            $jihuoma->save();
        }

        $m = Android::findOne([
            'jihuoma' => $this->getPageParam('jihuoma'),
        ]);

        if($m == false){
            $m = new Android();
            $m->createtime = time();
            $m->label = $m->fenlei = '';
            $m->user_id = $jihuoma->user_id;
            $m->jihuoma = $this->getPageParam('jihuoma');
        }
        $m->setAttributes($this->pageParam, false);
        $m->isonline = 1;
        \Yii::$app->services->autojs->jihuomaOnline($m);

        if( $m->save() ){
            return "ok";
        }
        throw new Exception(json_encode($m->errors));
    }


    public function actionClose()
    {
        $m = Android::findOne([
            'client_id' => $this->getPageParam('client_id')
        ]);
        $m->isonline = Gateway::isUidOnline($m->jihuoma);

        return $m->save();
    }


    public function actionFoundUser(){
        $dyh = $this->getPageParam('dyh');
        $user_id = $this->getPageParam('user_id');
        if( $dyh== false){
            return "no dyh";
        }

        $user = Douyinuser::findOne([
            'dyh' => $dyh,
            'user_id' => $user_id,
        ]);
        if($user == false){
            $user = new Douyinuser([
                'dyh' => $dyh,
                'user_id' => $user_id,
                'createtime' => time(),
            ]);
        }
        $user->setAttributes($this->pageParam,false);
        if($user->save(false) == false)
            throw new Exception(json_encode($user->errors));

        if($aryQun = $this->getPageParam('qunliao')){
            foreach ($aryQun as $one){
                $qun = Douyinqun::findOne([
                    'douyinhao' => $dyh,
                    'mingcheng' => $one['mingcheng'],
                    'user_id' => $user_id,
                ]);
                if($qun == false){
                    $qun = new Douyinqun([
                        'douyinhao' => $dyh,
                        'mingcheng' => $one['mingcheng'],
                        'createtime' => time(),
                        'isjoined' => 0,
                        'user_id' => $user_id,
                    ]);
                }
                $qun->setAttributes($one,false);
                $qun->renshu = str_replace(['(','人)'],['',''],$qun->renshu);
                $qun->menkan = str_replace('进群门槛：','',$qun->menkan);
                if($qun->save(false) == false)
                    throw new Exception(json_encode($qun->errors));
            }
        }

        return "okay";
    }

    public function actionQunGet(){
        $qun = Douyinqun::find()->andWhere([
            'isjoined' => 0,
            'menkan' => '无要求',
        ])->select('douyinhao')
            ->andWhere('join_stage is null')
            ->orderBy("rand()")
            ->groupBy('douyinhao')
            ->limit(1)->one();
        if($qun){
            return $qun;
        }
        return '';
    }

    public function actionQunJoin(){
        return Douyinqun::updateAll([
            'isjoined' => 1,
        ],[
            'douyinhao' => $this->getPageParam('douyinhao'),
            'menkan' => '无要求',
        ]);
    }
}
