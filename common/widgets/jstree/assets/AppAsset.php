<?php

namespace common\widgets\jstree\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package common\widgets\jstree\assets
 * @author Rf <1458015476@qq.com>
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@common/widgets/jstree/resources/';

    public $css = [
        'themes/default-rage/style.min.css',
    ];

    public $js = [
        'jstree.min.js',
    ];
}