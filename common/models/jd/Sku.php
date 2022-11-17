<?php

namespace common\models\jd;

use Yii;

/**
 * This is the model class for table "yii_sku".
 *
 * @property string $skuid
 * @property int $page
 * @property int $checktime
 */
class Sku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_sku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['skuid', 'page', 'checktime'], 'required'],
            [['page', 'checktime'], 'integer'],
            [['skuid'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'skuid' => 'Skuid',
            'page' => 'Page',
            'checktime' => 'Checktime',
        ];
    }
}
