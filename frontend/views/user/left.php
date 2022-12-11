
<?php
$path = Yii::$app->request->getPathInfo();

?>

<div class="col-xs-2">
    <ul class="nav nav-pills nav-stacked">
        <li role="presentation" class="<?= $path == 'user/index' ? 'active':'' ?>"><a href="<?= \common\helpers\Url::to(['user/index']) ?>">设备</a></li>
        <li role="presentation" class="<?= $path == 'user/user' ? 'active':'' ?>"><a href="<?= \common\helpers\Url::to(['user/user']) ?>">用户</a></li>
        <li role="presentation" class="<?= $path == 'user/qun' ? 'active':'' ?>"><a href="<?= \common\helpers\Url::to(['user/qun']) ?>">群</a></li>
        <li role="presentation" class="<?= $path == 'user/jihuoma' ? 'active':'' ?>"><a href="<?= \common\helpers\Url::to(['user/jihuoma']) ?>">激活码</a></li>
    </ul>
</div>