<?php

echo '这是' . Yii::$app->params['addon']['name'] . ' frontend 页面';

$qr = '/jdcrawler/login_qr.png?r='.bin2hex(random_bytes(10));
?>

京东扫码登录二维码
<img src="<?= $qr?>" />
