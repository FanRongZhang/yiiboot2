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
        $m = Android::findOne([
            'jihuoma' => $this->getPageParam('jihuoma')
        ]);

        //激活码被使用
        $jihuoma = Jihuoma::findOne([
            'jihuoma' => $m->jihuoma,
        ]);
        $jihuoma->had_used = 1;
        $jihuoma->save();

        if($m == false){
            $m = new Android();
            $m->createtime = time();
            $m->label = $m->fenlei = '';
            $m->user_id = $jihuoma->user_id;
        }
        $m->setAttributes($this->pageParam, false);
        $m->isonline = 1;

        Gateway::bindUid($m->client_id,$m->jihuoma);

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
        if( $dyh== false){
            return "no dyh";
        }

        $user = Douyinuser::findOne([
            'dyh' => $dyh,
        ]);
        if($user == false){
            $user = new Douyinuser([
                'dyh' => $dyh,
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
                ]);
                if($qun == false){
                    $qun = new Douyinqun([
                        'douyinhao' => $dyh,
                        'mingcheng' => $one['mingcheng'],
                        'createtime' => time(),
                        'isjoined' => 0,
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
