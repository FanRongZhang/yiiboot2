<?php

namespace services\jd;

use common\components\Service;
use linslin\yii2\curl\Curl;

/**
 *
 *
 * 广场试一试（发布支持凑单操作，同步到广场）
 *
 * 商业模式：专注    满减， 满减
 *
 *
 *
 * Class JdService
 * @package services\jd
 *
 */
class JdService extends Service
{
    public function getItemInfo($id)
    {
        $curl = new Curl();
        $curl->setHeaders([
           'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'cache-control' => 'max-age=0',
            'referer' => "https://item.jd.com/$id.html",
        ]);
        $jsonData = $curl->get("https://item-soa.jd.com/getWareBusiness?callback=&skuId=$id&num=1",false);

        //是否适合在平台上 凑着买
        $isFit = true;

        // text  跨自营/店铺满减 不要
        // text  多买优惠
        //text 满减
        //text 限购
        $aryPromo = [];
        if(isset($jsonData['promotion']) && isset($jsonData['promotion']['activity'])){
            foreach ($jsonData['promotion']['activity'] as $onePromo){
                //限购   基本  没任何 有利可图
                if($onePromo['text'] == '限购' || $onePromo['text']=='满额返券'){
                    $isFit = false;
                }
                //进口商品有每年的交易限额   基本  没任何 有利可图
                if(isset($onePromo['worldBuyInfo']) && isset($onePromo['worldBuyInfo']['nationName'])){
                    $isFit = false;
                }
                $aryPromo[] = $onePromo;
//                if($onePromo['text'][0] != '跨'){
//                    $aryPromo[] = $onePromo;
//                }
            }
        }

        //没任何满减 或  多件优惠
        if(  $aryPromo == false ){
            $isFit = false;
        }

        //直接给个 京东联盟 链接过去让用户直接买就行了
        $directBuyLink = '';
        if($isFit == false){

        }

        return [
            'isFit' => $isFit,
            'directBuyLink' => $directBuyLink,
            'name' => $jsonData['wareInfo']['wname'],
            'price' => $jsonData['price']['p'],
            'shopInfo' => $jsonData['shopInfo']['shop'],
            'adText' => isset($jsonData['adText']) ? $jsonData['adText'] : '',
            'aryPromo' => $aryPromo,
        ];
    }

}