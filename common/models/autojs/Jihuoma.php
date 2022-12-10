<?php

namespace common\models\autojs;

use Yii;

/**
 * This is the model class for table "yii_jihuoma".
 *
 * @property int $id
 * @property string $jihuoma
 * @property int $merchant_id
 * @property int $expire
 * @property int $had_used
 * @property int $user_id
 * @property int $createtime
 */
class Jihuoma extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_jihuoma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jihuoma', 'expire', 'createtime'], 'required'],
            [['merchant_id', 'expire', 'had_used', 'user_id', 'createtime'], 'integer'],
            [['jihuoma'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jihuoma' => 'Jihuoma',
            'merchant_id' => 'Merchant ID',
            'expire' => 'Expire',
            'had_used' => 'Had Used',
            'user_id' => 'User ID',
            'createtime' => 'Createtime',
        ];
    }
}
