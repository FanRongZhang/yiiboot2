<?php

namespace frontend\controllers;

use common\helpers\Url;
use Yii;
use yii\web\Controller;
use common\traits\BaseAction;
use common\behaviors\ActionLogBehavior;

/**
 * Class BaseController
 * @package frontend\controllers
 * @author Rf <1458015476@qq.com>
 */
class BaseController extends Controller
{
    use BaseAction;

    public $needLogin = false;


    /**
     * 页面提交参数
     * get post 一起存储
     *
     * @var array
     */
    protected $pageParam = [];

    /**
     * 获取页面参数
     * @param string $name
     * @param false $default
     * @return array|false|mixed
     */
    public function getPageParam($name='',$default=false){
        if($name == false){
            return $this->pageParam;
        }
        if(isset($this->pageParam[$name])){
            return $this->pageParam[$name];
        }
        return $default;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ]
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // 指定使用哪个语言翻译
        // Yii::$app->language = 'en';
         parent::init();

         if($this->needLogin && Yii::$app->getUser()->getIsGuest()){
             header("location: " . $this->message('请先登录','/site/login'));
             exit();
         }
    }
}