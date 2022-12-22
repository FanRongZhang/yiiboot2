<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use common\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">基础信息</h3>
            </div>
            <div class="panel-body">
                <?php echo '<?php'; ?>

                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //[
                        //    'label' => 'id',
                        //    'value' => $model->id,
                        //],
                <?php
                foreach ($generator->getColumnNames() as $attribute) {
                    if (true || in_array($attribute, $safeAttributes)) {
                        echo "'$attribute', \n";
                    }
                }
                ?>
                    ],
                ]);

                <?php echo '?>' ?>
            </div>
        </div>

    </div>
</div>
