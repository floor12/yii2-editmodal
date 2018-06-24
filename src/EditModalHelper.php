<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.09.2017
 * Time: 13:01
 */

namespace floor12\editmodal;


use rmrevin\yii\fontawesome\FontAwesome;
use rmrevin\yii\fontawesome\AssetBundle;
use floor12\editmodal\EditmodalAsset;
use yii\helpers\Html;
use \Yii;

class EditModalHelper
{

    public static function editBtn($path, $id, $class = "btn btn-default btn-sm")
    {
        AssetBundle::register(Yii::$app->getView());
        EditmodalAsset::register(Yii::$app->getView());
        return Html::a(FontAwesome::icon('pencil'), null, ['onclick' => "showForm('{$path}',{$id})", 'title' => 'редактировать', 'class' => $class]);
    }

    public static function deleteBtn($path, $id, $class, $container = '#items')
    {
        AssetBundle::register(Yii::$app->getView());
        EditmodalAsset::register(Yii::$app->getView());
        return Html::a(FontAwesome::icon('trash'), null, ['onclick' => "deleteItem('{$path}',{$id},'{$container}')", 'title' => 'удалить', 'class' => $class]);
    }
}