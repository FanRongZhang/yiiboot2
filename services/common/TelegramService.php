<?php

namespace services\common;

use linslin\yii2\curl\Curl;
use common\components\Service;


class TelegramService extends Service
{
    public function send($msg,$parse_mode='html')
    {
        $url = "https://api.telegram.org/bot5636105891:AAF6-PvbSadEIP6d1jzBcjvmmiG7J33XWOg/sendMessage";
        $params = [
            "parse_mode" => $parse_mode,
            'chat_id' => '-777847046',
            'text' => $msg,
        ];
        try {
            $curl = new Curl();
            $curl->setGetParams($params);
            $curl->setOptions([
                CURLOPT_TIMEOUT => 3,
                CURLOPT_RETURNTRANSFER => 1,
            ]);
            $res = $curl->get($url);
            if($curl->errorCode){
                return [
                    'code' => $curl->errorCode,
                    'msg' => $curl->errorText,
                ];
            }
            return $res;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

}