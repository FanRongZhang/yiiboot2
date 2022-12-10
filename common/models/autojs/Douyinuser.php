<?php

namespace common\models\autojs;

use Yii;

/**
 * This is the model class for table "yii_douyinuser".
 *
 * @property int $id
 * @property string $dyh
 * @property string $nick
 * @property string $huozan
 * @property string $guanzhu
 * @property string $fensi
 * @property string $zuopin
 * @property string $fensiqun
 * @property string $xihuan
 * @property string $note
 * @property int $createtime
 * @property int $user_id
 */
class Douyinuser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yii_douyinuser';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createtime'], 'required'],
            [['createtime', 'user_id'], 'integer'],
            [['dyh', 'nick', 'huozan', 'guanzhu', 'fensi', 'zuopin', 'fensiqun', 'xihuan'], 'string', 'max' => 200],
            [['note'], 'string', 'max' => 350],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dyh' => 'Dyh',
            'nick' => 'Nick',
            'huozan' => 'Huozan',
            'guanzhu' => 'Guanzhu',
            'fensi' => 'Fensi',
            'zuopin' => 'Zuopin',
            'fensiqun' => 'Fensiqun',
            'xihuan' => 'Xihuan',
            'note' => 'Note',
            'createtime' => 'Createtime',
            'user_id' => 'User ID',
        ];
    }
}
