<?php

namespace floor12\editmodal;

use yii\web\AssetBundle;

class EditModal2Asset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-editmodal/assets/';
    public $css = [
        'editModal2.css'
    ];
    public $js = [
        'editModal2.js'
    ];
    public $depends = [
        'floor12\notification\NotificationAsset',
    ];

}
