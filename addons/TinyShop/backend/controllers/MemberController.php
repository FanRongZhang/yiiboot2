<?php

namespace addons\TinyShop\backend\controllers;

use addons\TinyShop\common\traits\MemberInfo;
use common\traits\MemberSelectAction;

/**
 * Class MemberController
 * @package addons\TinyShop\backend\controllers
 * @author 小主科技 <1458015476@qq.com>
 */
class MemberController extends BaseController
{
    use MemberSelectAction, MemberInfo;
}