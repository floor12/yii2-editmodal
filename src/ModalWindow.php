<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 25.09.2017
 * Time: 11:09
 */

namespace floor12\editmodal;


use yii\helpers\Html;
use yii\helpers\Url;

class ModalWindow
{

    const TYPE_INFO = 0;
    const TYPE_OK = 1;
    const TYPE_ERROR = 2;

    private $_return;

    static function ajaxBtn($route, $data = null, $content, $title = null, $container = null, $confirmText = null, $class = null, $info)
    {
        $params = [
            'title' => $title,
            'onclick' => "
            if (confirm('{$confirmText}')) {
                  $.ajax({
                    'method':'POST',
                    'dataType':'json',
                    'url':'" . Url::to($route) . "',
                    'data':" . json_encode($data) . ",
                    'success':function(){
                        info('{$info}',1);
                        $.pjax.reload({container:'{$container}'});
                    },
                    error: function(response){
                        info(response.responseJSON.message,2);
                    }
                });
             }"
        ];
        $params['class'] = $class;
        return Html::a($content, null, $params);
    }

    static function showForm($route, $params)
    {
        $url = Url::to($route);
        $data = json_encode($params);
        return "showForm('{$url}',{$data})";
    }

    static function deleteItem($route, $id)
    {
        $url = Url::to($route);
        return "deleteItem('{$url}',{$id})";
    }


    public function hide()
    {
        $this->_return .= "hideFormModal();";
        return $this;
    }

    public function info($content, $type)
    {
        $this->_return .= "info(\"{$content}\",{$type});";
        return $this;
    }


    public function reloadContainer($container_name)
    {
        $this->_return .= "$.pjax.reload({container:\"{$container_name}\"});";
        return $this;
    }

    public function run()
    {
        return Html::script($this->_return);
    }
}