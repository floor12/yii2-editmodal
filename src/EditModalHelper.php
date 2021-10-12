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
        $onclick = ['onclick' => 'cancelModalEditSilent()'];
        return Html::button(IconHelper::CLOSE, array_merge($onclick, $options));
    }

    /**
     * @param array $options
     * @return string
     */
    public static function btnFullscreen($options = [])
    {
        $onclick = ['onclick' => 'editModalFullscreen()'];
        return Html::button(IconHelper::EXPAND, array_merge($onclick, $options));
    }


    /** Return button to show modal window
     * @param string $path Modal edit action route
     * @param integer $id Button html ID
     * @param string $class Button css class
     * @param string $content Content of button
     * @return string Html code
     */
    public static function editBtn($path, $id, $class = "btn btn-default btn-sm", $content = IconHelper::PENCIL)
    {
        $path = Url::toRoute([$path]);
        $path = str_replace(Yii::$app->urlManager->baseUrl, '', $path);
        EditModalAsset::register(Yii::$app->getView());
        return " " . Html::button($content, [
                'onclick' => "showForm('{$path}',{$id})",
                'title' => 'редактировать',
                'class' => $class
            ]);
    }

    /** Return button to show modal window
     * @param string $path Modal edit action route
     * @param integer $id Button html ID
     * @param string $class Button css class
     * @param string $content Content of button
     * @return string Html code
     */
    public static function copyBtn($path, $id, $class = "btn btn-default btn-sm", $content = IconHelper::COPY)
    {
        $path = Url::toRoute([$path]);
        $path = str_replace(Yii::$app->urlManager->baseUrl, '', $path);
        EditModalAsset::register(Yii::$app->getView());
        $params = json_encode(['id' => $id, 'copy' => 1]);
        return " " . Html::button($content, [
                'onclick' => "showForm('{$path}',{$params})",
                'title' => 'копировать',
                'class' => $class
            ]);
    }

    /** Return delete button
     * @param string $path DeleteAction route
     * @param integer $id Button html ID
     * @param string $class Buttin css class
     * @param string $container Pjax container DOM id to reload after deleting
     * @param string $content Content of button
     * @return string Html code
     */
    public static function deleteBtn($path, $id, $class = "btn btn-default btn-sm", $container = '#items', $content = IconHelper::TRASH)
    {
        $path = Url::toRoute($path);
        $path = str_replace(Yii::$app->urlManager->baseUrl, '', $path);
        EditModalAsset::register(Yii::$app->getView());
        return " " . Html::button($content, [
                'onclick' => "deleteItem('{$path}','{$id}','{$container}')",
                'title' => 'удалить',
                'class' => $class
            ]);
    }

    /** Return Javascript code to show modal form.
     * @param $route array EditModalAction route
     * @param int|array $params Some params
     * @return string JS code
     */
    public static function showForm($route, $params = 0, $modalParams = null, $silent = false)
    {
        $url = Url::toRoute($route);
        $url = str_replace(Yii::$app->urlManager->baseUrl, '', $url);
        $data = json_encode($params);
        $modalParams = json_encode($modalParams ?? ['keyboard' => false, 'backdrop' => 'static']);
        $silent = $silent ? 'true' : 'false';
        return 'showForm("' . $url . '",' . $data . ',' . $modalParams . ',' . $silent . ')';
    }

    /** Return Javascript code to delete object.
     * @param $route array DeleteAction route
     * @param $id integer Object ID
     * @return string JS code
     */
    public static function deleteItem($route, $id)
    {
        $url = Url::toRoute($route);
        $url = str_replace(Yii::$app->urlManager->baseUrl, '', $url);
        return "deleteItem('{$url}',{$id})";
    }
}
