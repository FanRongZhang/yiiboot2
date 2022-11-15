<?php

namespace services\jd;

use common\components\Service;
use common\helpers\ArrayHelper;
use common\helpers\RegularHelper;
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
    public function getItemInfo($id, $isMergeInfo = true)
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
                if($onePromo['text'] == '限购' || $onePromo['text'] =='满额返券'){
                    $isFit = false;
                }
                //进口商品有每年的交易限额   基本  没任何 有利可图
                if(isset($onePromo['worldBuyInfo']) && isset($onePromo['worldBuyInfo']['nationName'])){
                    $isFit = false;
                }

//                if($isFit && $onePromo['text'][0] != ''){}
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

        $price = $jsonData['price']['p'];
        $name = $jsonData['wareInfo']['wname'];
        $promotionInfo = [
            'isFit' => $isFit,
            'directBuyLink' => $directBuyLink,
            'name' => $name,
            'price' => $price,
//            'shopInfo' => $jsonData['shopInfo']['shop'],
//            'adText' => isset($jsonData['adText']) ? $jsonData['adText'] : '',
            'aryPromo' => $aryPromo,
        ];

        $isUp = 0;//上架
        $meiDanJian = 0;
        if($isFit){
            $youfei = 5;//邮费
            $wodefuwufei = 3;//我的服务费
            $e_wai_pay = $youfei + $wodefuwufei;
            foreach ($aryPromo as $one) {
//                preg_match('/^满([\d+]*)\D+([\d+]*)/','满199元减100元', $matches);
                preg_match('/^满([\d+]*)\D+([\d+]*)/', $one['value'], $matches);
                if ($matches && count($matches) == 3 && is_numeric($matches[1]) && is_numeric($matches[2])) {
                    $man = $matches[1];//满多少
                    $jian = $matches[2];//减去的金额
                    if ($man > $price) {
                        $danshu = ceil($man / $price);//获取满减需要购买的件数
                        $meiDanJian = $jian / $danshu;//每单对于用户来说优惠了多少钱

//                        //用户除去给我的钱，还可以额外至少节省20元钱，那么就上架
//                        $jieyuele = 20;
//                        if($meiDanJian - $e_wai_pay >= $jieyuele){
//                            $isUp = 1;
//                        }
                    }
                }
            }
        }


        return ArrayHelper::merge($isMergeInfo ? $this->getInfoViaZTK($id) : [], [
            'promotionInfo' => $promotionInfo,
        ]);
    }

    public $appkey_ztk = 'b05b918afdee42fc9400f153f6620883';
    private function getInfoViaZTK($id){
        $url ="https://api.zhetaoke.com:10001/api/open_jing_union_open_goods_query.ashx?".http_build_query([
                'appkey' => $this->appkey_ztk,
                'skuIds' => $id,
            ]);
        $data = file_get_contents($url);
        $aryJson = \Qiniu\json_decode($data, true);
//        return $aryJson;
        return \Qiniu\json_decode( $aryJson['jd_union_open_goods_query_response']['result'] , true)['data'];
    }

}