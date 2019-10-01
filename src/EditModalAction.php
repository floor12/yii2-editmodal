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
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class EditModalAction extends Action
{
    public $model;
    public $message = 'Объект сохранен';
    public $view = '_form';
    public $logic;
    public $access = true;
    public $successJs;
    public $successHtml;
    public $scenario;
    public $viewParams = [];
    public $container = '#items';

    private $_return;
    private $_modelObject;

    public function run($id = 0)
    {
        if (!$this->access)
            throw new ForbiddenHttpException();

        if (!$id)
            $this->_modelObject = new $this->model;
        else {
            $classname = $this->model;
            $this->_modelObject = $classname::findOne(intval($id));
            if (!$this->_modelObject)
                throw new NotFoundHttpException("Object with id {$id} not found");
        }

        if (is_string($this->scenario))
            $this->_modelObject->setScenario($this->scenario);


        if (is_object($this->scenario))
            $this->_modelObject->setScenario(call_user_func($this->scenario, $this->_modelObject));


        if (!$this->logic && ($this->_modelObject->load(Yii::$app->request->post())) && $this->_modelObject->save()) {
            return $this->getReturnString();
        }

        if ($this->logic && \Yii::$app->request->isPost) {
            if (\Yii::createObject($this->logic, [$this->_modelObject, \Yii::$app->request->post(), \Yii::$app->user->identity])->execute())
                return $this->getReturnString();
        }

        $this->viewParams['model'] = $this->_modelObject;
        return $this->controller->renderAjax($this->view, $this->viewParams);
    }


    protected function getReturnString()
    {
        if ($this->successHtml)
            $this->_return = call_user_func($this->successHtml, $this->_modelObject);
        elseif ($this->successJs)
            $this->_return = Html::tag('script', $this->successJs);
        else
            $this->_return = \Yii::createObject(ModalWindow::class, [])
                ->reloadContainer($this->container)
                ->info($this->message, ModalWindow::TYPE_OK)
                ->hide()
                ->run();
        return $this->_return;
    }

}
