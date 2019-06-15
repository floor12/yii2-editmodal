<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.09.2017
 * Time: 13:01
 */

namespace floor12\editmodal;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/** Helper class to show control elements.
 * Class EditModalHelper
 * @package floor12\editmodal
 */
class EditModalHelper
{

    public static function open(string $url, $params = [])
    {

    }

    /**
     * @param array $options
     * @return string
     */
    public static function btnClose($options = [])
    {
        $onclick = ['onclick' => 'f12editmodal.close()'];
        return Html::button(IconHelper::CLOSE, array_merge($onclick, $options));
    }

    /**
     * @param array $options
     * @return string
     */
    public static function btnFullscreen($options = [])
    {
        $onclick = ['onclick' => 'f12editmodal.expand()'];
        return Html::button(IconHelper::EXPAND, array_merge($onclick, $options));
    }









    /** Return button to show modal window
     * @param string $path Modal edit action route
     * @param integer $id Object ID
     * @param string $class Object Class
     * @return string Html code
     */
    public static function editBtn($path, $id, $class = "btn btn-default btn-sm")
    {
        $path = Url::toRoute($path);
        EditModalAsset::register(Yii::$app->getView());
        return " " . Html::button(IconHelper::PENCIL, [
                'onclick' => "showForm('{$path}',{$id})",
                'title' => 'редактировать',
                'class' => $class
            ]);
    }

    /** Return delete button
     * @param string $path DeleteAction route
     * @param integer $id Object ID
     * @param string $class Object Class
     * @param string $container Pjax container DOM id to reload after deleting
     * @return string Html code
     */
    public static function deleteBtn($path, $id, $class = "btn btn-default btn-sm", $container = '#items')
    {
        $path = Url::toRoute($path);
        EditModalAsset::register(Yii::$app->getView());
        return " " . Html::button(IconHelper::TRASH, [
                'onclick' => "deleteItem('{$path}',{$id},'{$container}')",
                'title' => 'удалить',
                'class' => $class
            ]);
    }

    /** Return Javascript code to show modal form.
     * @param $route array EditModalAction route
     * @param int|array $params Some params
     * @return string JS code
     */
    public static function showForm($route, $params = 0, $modalParams = null)
    {
        $url = Url::to($route);
        $data = json_encode($params);
        if ($modalParams !== null)
            $modalParams = json_encode($modalParams);
        return 'showForm("' . $url . '",' . $data . ',' . $modalParams . ')';
    }

    /** Return Javascript code to delete object.
     * @param $route array DeleteAction route
     * @param $id integer Object ID
     * @return string JS code
     */
    public static function deleteItem($route, $id)
    {
        $url = Url::to($route);
        return "deleteItem('{$url}',{$id})";
    }
}