<?php
use \yii\helpers\Html;
use common\helpers\Url;
use kartik\grid\GridView;


/** @var \yii\web\View $this */


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


$(".g5").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=upgrade',
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


$(".g6").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=checkonline',
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

$(".g7").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=doagain',
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

$(".g8").on("click", function () {

    var keys = $("#grid").yiiGridView("getSelectedRows");
     $.ajax({
            url: '$url?action=shutdown',
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
    <?php
    echo $this->render('left');
    ?>
    <div class="col-xs-10">
        <div style="margin-bottom: 20px;">
            <?= Html::a('推荐操作', "javascript:void(0);", ['class' => 'btn btn-primary g1']) ?>
            <?= Html::a('进群操作', "javascript:void(0);", ['class' => 'btn btn-primary g2']) ?>
            <?= Html::a('搜索操作', "javascript:void(0);", ['class' => 'btn btn-primary g3']) ?>
            <?= Html::a('设备信息展示', "javascript:void(0);", ['class' => 'btn btn-primary g4']) ?>
            <?= Html::a('升级到最新代码', "javascript:void(0);", ['class' => 'btn btn-primary g5']) ?>
            <?= Html::a('检查激活码使用情况', "javascript:void(0);", ['class' => 'btn btn-primary g6']) ?>
            <?= Html::a('重复上次操作', "javascript:void(0);", ['class' => 'btn btn-primary g7']) ?>
            <?= Html::a('设备下线', "javascript:void(0);", ['class' => 'btn btn-primary g8']) ?>
        </div>

        <div>

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
                        'attribute' => 'isonline',
                        'label' => '状态',
                        'value' => function($model){
                            return $model->isonline?'使用中':'未使用';
                        }
                    ],
                    [
                        'label' => '设备',
                        'value' => function($model){
                            return $model->brand . ' ' . $model->product . ' ' . $model->w .'x'.$model->h;
                        }
                    ],
//            'product',
//            'release',
//            'android_id',
                    [
                        'attribute' => 'can_upgrade_code',
                        'label' => '代码',
                        'value' => function($model){
                            if($model->can_upgrade_code == 1){
                                return '可同步,上次更新时间：'.date('Y-m-d H:i:s',$model->codetime);
                            }else{
                                return '最新';
                            }
                        }
                    ],
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