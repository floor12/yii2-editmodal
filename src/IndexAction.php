<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26.12.2016
 * Time: 11:31
 */

namespace floor12\editmodal;

use Yii;
use yii\base\Action;
use yii\base\ErrorException;
use yii\web\ForbiddenHttpException;

class IndexAction extends Action
{
    public $model;
    public $view = 'index';
    public $layout;
    public $access = true;
    public $viewParams = [];

    private $_return;
    private $_modelObject;

    public function run($id = 0)
    {
        if (!$this->access)
            throw new ForbiddenHttpException();

        if ($this->layout)
            $this->controller->layout = $this->layout;

        if ($this->model) {
            $this->_modelObject = new $this->model;
            $this->_modelObject->load(Yii::$app->request->get());
            $this->viewParams['model'] = $this->_modelObject;
        }

        Yii::$app->response->headers->add('Vary', 'X-PJAX,X-Requested-With');
        
        return $this->controller->render($this->view, $this->viewParams);
    }


}
