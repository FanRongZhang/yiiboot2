<?php

namespace services\jd;

use common\components\Service;
use common\helpers\ArrayHelper;
use common\helpers\RegularHelper;
use common\models\jd\Goods;
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
    public function saveAfterGetItemInfo($id){
        $ary = $this->getItemInfo($id);

        $m = new Goods();
        $m->brandCode = $ary['brandCode'];
        $m->brandName = $ary['brandName'];
        $m->cid1 = strval($ary['categoryInfo']['cid1']);
        $m->cid1Name = $ary['categoryInfo']['cid1Name'];
        $m->cid2 = strval($ary['categoryInfo']['cid2']);
        $m->cid2Name = $ary['categoryInfo']['cid2Name'];
        $m->cid3 = strval($ary['categoryInfo']['cid3']);
        $m->cid3Name = $ary['categoryInfo']['cid3Name'];
        $m->comments = $ary['comments'];
        $m->imageList = json_encode($ary['imageInfo']['imageList'],JSON_UNESCAPED_UNICODE);
        $m->whiteImage = $ary['imageInfo']['whiteImage'];
        $m->isHot = $ary['isHot'];
        $m->isJdSale = $ary['isJdSale'];
        $m->shopId = strval($ary['shopInfo']['shopId']);
        $m->shopLevel = $ary['shopInfo']['shopLevel'];
        $m->shopName = $ary['shopInfo']['shopName'];
        $m->stockState = 1;
        $m->materialUrl = $ary['materialUrl'];
        $m->price = $ary['promotionInfo']['price'];
        $m->skuName = $ary['skuName'];
        $m->skuId = strval($ary['skuId']);
        $m->promotionInfoJson = json_encode($ary['promotionInfo'],JSON_UNESCAPED_UNICODE);
        $m->couInfoJson = json_encode($ary['promotionInfo']['couInfo'],JSON_UNESCAPED_UNICODE);
        $m->is_up = $ary['promotionInfo']['isFit'] ? 1 :0;
        $m->program_last_check_time = time();
        return $m->save();
    }

    public function getItemInfo($id, $isMergeInfo = true)
    {
        $curl = new Curl();
        $curl->setHeaders([
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'cache-control' => 'max-age=0',
            'referer' => "https://item.jd.com/$id.html",
        ]);
        $jsonData = $curl->get("https://item-soa.jd.com/getWareBusiness?callback=&skuId=$id&num=1", false);

        // text  跨自营/店铺满减 不要
        // text  多买优惠
        //text 满减
        //text 限购
        $aryPromo = [];
        if (isset($jsonData['promotion']) && isset($jsonData['promotion']['activity'])) {
            foreach ($jsonData['promotion']['activity'] as $onePromo) {
                $aryPromo[] = $onePromo;

                //限购   基本  没任何 有利可图
//                if ($onePromo['text'] == '限购' || $onePromo['text'] == '满额返券') {
//                    $isFit = false;
//                }
//                //进口商品有每年的交易限额   基本  没任何 有利可图
//                if (isset($onePromo['worldBuyInfo']) && isset($onePromo['worldBuyInfo']['nationName'])) {
//                    $isFit = false;
//                }

//                $aryPromo[] = $onePromo;
//                if($onePromo['text'][0] != '跨'){
//                    $aryPromo[] = $onePromo;
//                }
            }
        }

        $price = $jsonData['price']['p'];
        $name = $jsonData['wareInfo']['wname'];

        //是否适合在平台上 凑着买
        $isFit = false;

        //算出每一个满减的金额情况
        $couInfo = [];
        foreach ($aryPromo as $onePromo) {
            if($onePromo['text'] != '满减'){
                continue;
            }

            $aryManJian = [];
            //满199元减20元，满299元减30元，满399元减50元
            if(explode('减',$onePromo['value']) >= 2){
                $aryManJian = explode('，',$onePromo['value']);
            }else{
                $aryManJian[] = $onePromo['value'];
            }

            foreach ($aryManJian as $oneManJian) {
                preg_match('/^满([\d+]*)\D+([\d+]*)/', $oneManJian, $matches);
                if ($matches && count($matches) == 3 && is_numeric($matches[1]) && is_numeric($matches[2])) {
                    $man = $matches[1];//满多少
                    $jian = $matches[2];//减去的金额
                    if ($man > $price) {//满的金额需要大于单个卖价，不然凑屁
                        $isFit = true;
                        $danshu = ceil($man / $price);//获取满减需要购买的件数
                        $meiDanJian = $jian / $danshu;//每单对于用户来说优惠了多少钱

                        //相同单量情况下，只保留对用户最划算的
                        $meiDanJieSheng = round($meiDanJian, 2);
                        if( isset($couInfo[$danshu]) == false || $meiDanJieSheng > $couInfo[$danshu]['sheng']) {
                            $couInfo[$danshu] = [
                                'man' => $man,//满
                                'jian' => $jian,//减
                                'danshu' => $danshu,//几单达到满
                                'meidansheng' => $meiDanJieSheng,//每单省
                            ];
                        }
                    }
                }
            }
        }

        $promotionInfo = [
            'isFit' => $isFit,
            'name' => $name,
            'price' => $price,
//            'shopInfo' => $jsonData['shopInfo']['shop'],
//            'adText' => isset($jsonData['adText']) ? $jsonData['adText'] : '',
            'aryPromo' => $aryPromo,
            'couInfo' => $couInfo,
        ];

        return ArrayHelper::merge($isMergeInfo ? $this->getInfoViaZTK($id) : [], [
            'promotionInfo' => $promotionInfo,
        ]);
    }

    public $appkey_ztk = 'b05b918afdee42fc9400f153f6620883';
    private function getInfoViaZTK($id){
        $url ="https://api.zhetaoke.com:10001/api/open_jing_union_open_goods_query.ashx?".http_build_query([
                'appkey' => $this->appkey_ztk,
                'skuIds' => $id,
                'area' => \Yii::$app->params['jd_area_id'],
            ]);
        $data = file_get_contents($url);
        $aryJson = \Qiniu\json_decode($data, true);
//        return $aryJson;
        return \Qiniu\json_decode( $aryJson['jd_union_open_goods_query_response']['result'] , true)['data'][0];
    }

}