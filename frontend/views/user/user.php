<?php

use common\helpers\Html;
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

SCRIPT;
$this->registerJs($script);

?>

<div class="row">
    <?php
    echo $this->render('left');
    ?>
    <div class="col-xs-10">


        <div>

            <?= GridView::widget([
                    'options' =>['id'=>'grid'],

                'dataProvider' => $dataProvider,
//                'tableOptions' => ['class' => 'table table-hover'],
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
//                    [
//                        'class' => 'yii\grid\ActionColumn',
//                        'header' => '操作',
//                        'template' => '{edit} ',
//                        'buttons' => [
//                            'edit' => function($url, $model, $key){
//                                return Html::edit(['edit', 'id' => $model->id]);
//                            },
//                        ]
//                    ]
                ]
            ]); ?>
        </div>

    </div>
</div>