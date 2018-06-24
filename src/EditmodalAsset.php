<?php

namespace floor12\editmodal;

use yii\web\AssetBundle;

class EditmodalAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-editmodal/assets/';
    public $css = [];
    public $js = [
        'editmodal.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'floor12\notification\NotificationAsset',
    ];

}
