<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26.12.2016
 * Time: 11:31
 */

namespace floor12\editmodal;


use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use \Yii;

class EditModalAction extends Action
{
    public $model;
    public $message = 'Объект сохранен';
    public $view = '_form';
    public $logic;
    public $access = true;
    public $successJs;
    public $scenario;

    private $_return;

    public function init()
    {
        if (!$this->access)
            throw new ForbiddenHttpException();

        if ($this->successJs)
            $this->_return = "<script>{$this->successJs}</script>";
        else
            $this->_return = "<script>hideFormModal();$.pjax.reload({container:\"#items\"});info('{$this->message}', 1);</script>";

        parent::init();
    }

    public
    function run($id)
    {
        if (!$id)
            $model = new $this->model;
        else {
            $classname = $this->model;
            $model = $classname::findOne($id);
            if (!$model)
                throw new NotFoundHttpException("Object with id {$id} not found");
        }

        if ($this->scenario)
            $model->setScenario($this->scenario);
        if (!$this->logic && ($model->load(Yii::$app->request->post())) && $model->save()) {
            return $this->_return;
        }

        if ($this->logic && \Yii::$app->request->isPost) {
            if (\Yii::createObject($this->logic, [$model, \Yii::$app->request->post(), \Yii::$app->user->identity])->execute())
                return $this->_return;
        }

        return $this->controller->renderAjax($this->view, ['model' => $model]);
    }
}