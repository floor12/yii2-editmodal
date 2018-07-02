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
        $this->_return .= "if ($(\"{$container_name}\").length > 0) $.pjax.reload({container:\"{$container_name}\"});";
        return $this;
    }

    public function runFunction($function)
    {
        $this->_return .= "{$function};";
        return $this;
    }


    public function run()
    {
        return Html::script($this->_return);
    }
}