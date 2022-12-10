<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '抖音用户';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit']) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

            //'id',
            [ 'attribute' => 'dyh',  'label' => '抖音号',],
            [ 'attribute' => 'nick',  'label' => '昵称',],
            [ 'attribute' => 'huozan',  'label' => '获赞',],
//            [ 'attribute' => 'guanzhu',  'label' => '关注',],
            [ 'attribute' => 'fensi',  'label' => '粉丝',],
            [ 'attribute' => 'zuopin',  'label' => '作品数',],
            [ 'attribute' => 'fensiqun',  'label' => '粉丝群',],
//            'xihuan',
            //'note',
            'createtime:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} ',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit', 'id' => $model->id]);
                },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
