<?php

namespace addons\autocellphone\common\models;

use Yii;

/**
 * This is the model class for table "yii_douyinqun".
 *
 * @property int $id
 * @property string $douyinhao
 * @property string $mingcheng
 * @property int $renshu
 * @property string $menkan
 * @property int $isjoined
 * @property int $createtime
 * @property int $user_id
 */
class Douyinqun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_autojs_douyinqun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['douyinhao', 'mingcheng', 'renshu', 'menkan', 'isjoined', 'createtime', 'user_id'], 'required'],
            [['renshu', 'isjoined', 'createtime', 'user_id'], 'integer'],
            [['douyinhao', 'mingcheng', 'menkan'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'douyinhao' => 'Douyinhao',
            'mingcheng' => 'Mingcheng',
            'renshu' => 'Renshu',
            'menkan' => 'Menkan',
            'isjoined' => 'Isjoined',
            'createtime' => 'Createtime',
            'user_id' => 'User ID',
        ];
    }
}
