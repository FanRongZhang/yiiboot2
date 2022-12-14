<?php

namespace addons\autocellphone\services;

use common\components\Service;
use common\helpers\StringHelper;
use addons\autocellphone\common\models\Jihuoma;
use addons\autocellphone\common\models\Android;
use GatewayWorker\Lib\Gateway;

/**
 * Class AutojsService
 * @package services\autojs
 */
class AutojsService extends Service
{

    public function makeJiHuoma($n,$expire,$user_id=0,$merchant_id=0){
        while (--$n >=0 ) {
            $modelJiHuoMa = new Jihuoma();
            $modelJiHuoMa->user_id = $user_id;
            $modelJiHuoMa->expire = $expire;
            $modelJiHuoMa->merchant_id = $merchant_id;
            $modelJiHuoMa->createtime = time();
            $modelJiHuoMa->had_used = 0;
            $modelJiHuoMa->jihuoma = StringHelper::uuid();
            $modelJiHuoMa->save();
        }
    }

    /**
     * 激活码在线
     * 把激活码这里作为用户ID使用
     * @param Android $android
     */
    public function jihuomaOnline(Android $android){
        Gateway::bindUid($android->client_id,$android->jihuoma);
    }

    /**
     * 激活码是否在线
     * 把激活码这里作为用户ID使用
     * @param Android $android
     * @return int
     */
    public function isJihuomaOnline(Android $android){
        try {
            return Gateway::isUidOnline($android->jihuoma);
        }catch (\Throwable $e){
            return 0;
        }
    }

    public function wsClose(Android $android){
        try{
            Gateway::closeClient($android->client_id);
        }catch (\Throwable $e){}
    }

}