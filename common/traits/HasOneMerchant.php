<?php

namespace common\traits;

use common\models\merchant\Merchant;

/**
 * Trait HasOneMerchant
 * @package common\traits
 * @author 小主科技 <1458015476@qq.com>
 */
trait HasOneMerchant
{
    /**
     * 关联商户
     *
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::class, ['id' => 'merchant_id'])->select([
            'id',
            'title',
            'cover',
            'address_name',
            'address_details',
            'longitude',
            'latitude',
        ])->cache(60);
    }
}