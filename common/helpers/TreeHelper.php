<?php

namespace common\helpers;

/**
 * Class TreeHelper
 * @package common\helpers
 * @author 小主科技 <1458015476@qq.com>
 */
class TreeHelper
{
    /**
     * @return string
     */
    public static function prefixTreeKey($id)
    {
        return "tr_$id ";
    }

    /**
     * @return string
     */
    public static function defaultTreeKey()
    {
        return 'tr_0 ';
    }
}