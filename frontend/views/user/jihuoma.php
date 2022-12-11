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
            <div class="alert-info alert">激活码请联系微信号 xxxx 获取</div>
            <div class="alert-info alert">代理招募，请联系 xxxx </div>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'visible' => false,
                    ],
                    'id',
                    [ 'attribute' => 'jihuoma',  'label' => '激活码',],
                    [ 'attribute' => 'expire',  'label' => '过期时间','format'=>'datetime'],
                    [ 'attribute' => 'had_used',  'label' => '已使用','value'=>function($m){return $m->had_used?'是':'否';},],
                    'createtime:datetime',
                ]
            ]); ?>

        </div>

    </div>
</div>