<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use common\helpers\Html;
use common\helpers\Url;
use yii\bootstrap\ActiveForm;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\grid\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= "<?= " ?>Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= "<?= " ?>Html::create(['edit']) ?>
                </div>
            </div>
            <div class="box-body table-responsive">

                <div class="post-search">
                    <?php echo '<?php'; ?>
                    $this->registerCss('.form-group{display:inline-block !important;width:200px;}');
                    $form = ActiveForm::begin([
                        'method' => 'get',
                        'layout' => 'default',
                    ]);<?php echo ' ?>'; ?>

                    <?php
                    foreach ($generator->getColumnNames() as $attribute) {
                        if (in_array($attribute, $safeAttributes)) {
                            echo "<?php echo \$form->field(\$searchModel, '$attribute'); ?> \n";
                        }
                    }
                    ?>

                    <div class="form-group">
                        <?php echo ' <?='; ?> Html::submitButton('搜索', ['class' => 'btn btn-primary'])  <?php echo " ?>\n"; ?>
                        <?php echo ' <?='; ?> Html::submitButton('重置', ['class' => 'btn btn-default'])  <?php echo ' ?>'; ?>
                    </div>

                    <?php echo '<?php'; ?> ActiveForm::end(); ?>
                </div>


<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        //'filterModel' => $searchModel,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            //'" . $name . "',\n";
        }
    }
} else {
    $listFields = !empty($generator->listFields) ? $generator->listFields : [];
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (in_array($column->name, $listFields)) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{show} {edit} {delete}',
                'buttons' => [

                    'show' => function($url, $model, $key){
                        return Html::linkButton(['show','id'=>$model->id],'查看');
                    },

                    'edit' => function($url, $model, $key){
                        return Html::linkButton(['edit','id'=>$model->id],'编辑');
                        //return Html::edit(['edit', 'id' => $model->id]);
                    },

                    'delete' => function($url, $model, $key){
                            return Html::delete(['delete', 'id' => $model->id]);
                    },

                ]
            ]
    ]
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
            </div>
        </div>
    </div>
</div>
