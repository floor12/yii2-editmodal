<?php

namespace floor12\editmodal;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EditmodalAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-editmodal/assets/';
    public $css = [
        'editmodal.css'
    ];
    public $js = [
        'editmodal.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];

}
