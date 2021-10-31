<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>\<?= $appID ?>\modules\<?= $versions ?>;

/**
 * Class Module
 * @package addons\<?= $model->name;?>\<?= $appID ?>\modules\<?= $versions ?>
 * @author 小主科技 <1458015476@qq.com>
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'addons\<?= $model->name;?>\<?= $appID ?>\modules\<?= $versions ?>\controllers';

    public function init()
    {
        parent::init();
    }
}