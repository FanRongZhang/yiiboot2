<?php

namespace services\autojs;

use common\components\Service;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\models\autojs\Jihuoma;
use common\models\jd\Goods;
use linslin\yii2\curl\Curl;

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
}