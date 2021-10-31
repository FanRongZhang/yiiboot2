<?php

namespace common\enums;

/**
 * Class InvoiceTypeEnum
 * @package common\enums
 * @author 小主科技 <1458015476@qq.com>
 */
class InvoiceTypeEnum extends BaseEnum
{
    const COMPANY = 1;
    const PERSONAGE = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::COMPANY => '公司',
            self::PERSONAGE => '个人',
        ];
    }
}