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
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'visible' => false,
                    ],
                    'id',
                    [ 'attribute' => 'douyinhao',  'label' => '抖音号',],
                    [ 'attribute' => 'mingcheng',  'label' => '名称',],
                    [ 'attribute' => 'renshu',  'label' => '人数',],
                    [ 'attribute' => 'menkan',  'label' => '门槛',],
                    [ 'attribute' => 'isjoined',  'label' => '已入群',],
                    'createtime:datetime',
                ]
            ]); ?>

        </div>

    </div>
</div>