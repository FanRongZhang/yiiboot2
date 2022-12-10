<?php

use common\helpers\Html;
use common\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '机器';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['send-action']);
$script = <<<SCRIPT
$(".g1").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=shuashouyetuijian',
            data: {ids:keys},
            type: 'post',
            success: function (t) {
                alert('成功')
            },
            error: function () {
                alert("删除失败！")
            }
     
     })
    
});

$(".g2").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=jinqun',
            data: {ids:keys},
            type: 'post',
            success: function (t) {
                alert('成功')
            },
            error: function () {
                alert("删除失败！")
            }
     
     })
    
});


$(".g3").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=search',
            data: {ids:keys},
            type: 'post',
            success: function (t) {
                alert('成功')
            },
            error: function () {
                alert("删除失败！")
            }
     
     })
    
});


$(".g4").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=',
            data: {ids:keys},
            type: 'post',
            success: function (t) {
                alert('成功')
            },
            error: function () {
                alert("删除失败！")
            }
     
     })
    
});
SCRIPT;
$this->registerJs($script);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::a('批量刷首页推荐', "javascript:void(0);", ['class' => 'btn btn-primary g1']) ?>
                    <?= Html::a('批量进群', "javascript:void(0);", ['class' => 'btn btn-primary g2']) ?>
                    <?= Html::a('批量重新操作', Url::to(['reset']), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('批量重新搜索', "javascript:void(0);", ['class' => 'btn btn-primary g3']) ?>
                    <?= Html::a('设备信息展示', "javascript:void(0);", ['class' => 'btn btn-primary g4']) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'options' =>['id'=>'grid'],

        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name'=>'id',
            ],//复选框列
            [ 'attribute' => 'jihuoma',  'label' => '激活码',],
            [ 'attribute' => 'label', 'class'=>'kartik\grid\EditableColumn', 'label' => '标签', ],
            [ 'attribute' => 'fenlei', 'class'=>'kartik\grid\EditableColumn', 'label' => '分类', ],
            [
                    'label' => '分辨率',
                'value' => function($model){
        return $model->w .'x'.$model->h;
                }
            ],
//            'brand',
//            'product',
//            'release',
//            'android_id',
//            [ 'attribute' => 'isonline',  'label' => '是否在线',
//                'value' => function($model){
//                    return $model->isonline?'在线':'离线';
//                }],
            [ 'attribute' => 'keyword', 'class'=>'kartik\grid\EditableColumn',  'label' => '关键字',],
            [
                'attribute' => 'action',
                'contentOptions' => function($model) {
                    return ['class' => 'input-editbale-item', 'data-attribute' => 'action', 'data-value' => $model->action, 'data-url' => Url::to(['send-action?id=' . $model->id])];
                },
            ],
            [ 'attribute' => 'createtime','label' => '创建时间','format'=>'datetime' ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'header' => '操作',
//                'template' => '',
//                'buttons' => [
//                    'edit' => function($url, $model, $key){
//                        return Html::edit(['edit', 'id' => $model->id]);
//                    },
//                    'delete' => function($url, $model, $key){
//                        return Html::delete(['edit', 'id' => $model->id]);
//                    },
//                ]
//            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>

<script>
    //https://blog.csdn.net/tang05709/article/details/101758604
//http://old.xiaochengfu.com/index.php/index/detail/aid/68.html
</script>

