<?php

namespace addons\TinyShop\api\modules\v1\controllers\member;

use Yii;
use common\models\member\Invoice;
use api\controllers\UserAuthController;

/**
 * 发票
 *
 * Class InvoiceController
 * @package addons\TinyShop\api\modules\v1\controllers\member
 * @author 小主科技 <1458015476@qq.com>
 */
class InvoiceController extends UserAuthController
{
    /**
     * @var Invoice
     */
    public $modelClass = Invoice::class;

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionDefault()
    {
        return Yii::$app->tinyShopService->memberInvoice->findDefaultByMemberId(Yii::$app->user->identity->member_id);
    }
}