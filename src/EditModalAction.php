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
    public $showNextOnSuccess = false;
    public $showFormOnSuccess = false;
    public $nextFormUrl = null;
    public $viewParams = [];
    public $container = '#items';
    public $strictIntegerKey = true;

    private $returnString;
    private $modelObject;

    public function run($id = 0, $copy = null)
    {

        if (!$id)
            $this->modelObject = new $this->model;
        else {
            $classname = $this->model;
            $this->modelObject = $classname::findOne($this->strictIntegerKey ? intval($id) : $id);
            if (!$this->modelObject)
                throw new NotFoundHttpException("Object with id {$id} not found");
        }

        if ($copy === '1') {
            $oldModel = $this->modelObject;
            $this->modelObject = new $this->model;
            $this->modelObject->attributes = $oldModel->attributes;
        }

        if (is_callable($this->access))
            $this->access = call_user_func($this->access, $this->modelObject);

        if (is_bool($this->access) && $this->access == false)
            throw new ForbiddenHttpException();

        if (is_string($this->scenario))
            $this->modelObject->setScenario($this->scenario);


        if (is_object($this->scenario))
            $this->modelObject->setScenario(call_user_func($this->scenario, $this->modelObject));

        if (Yii::$app->request->post('showNextOnSuccess'))
            $this->showNextOnSuccess = true;

        if (Yii::$app->request->post('showFormOnSuccess') && null !== $this->nextFormUrl)
            $this->showFormOnSuccess = true;

        if (!$this->logic &&
            ($this->modelObject->load(Yii::$app->request->post())) &&
            $this->modelObject->save()
        ) {
            return $this->getReturnData();
        }

        if (
            $this->logic &&
            \Yii::$app->request->isPost) {
            if (\Yii::createObject($this->logic, [$this->modelObject, \Yii::$app->request->post(), \Yii::$app->user->identity])->execute())
                return $this->getReturnData();
        }
        $this->viewParams['model'] = $this->modelObject;
        return $this->controller->renderAjax($this->view, $this->viewParams);
    }

    protected function getReturnData()
    {
        if ($this->showNextOnSuccess)
            return $this->loadNext();
        elseif ($this->showFormOnSuccess)
            return $this->loadNextForm();
        else
            return $this->getSuccessReturnString();
    }

    protected function loadNextForm()
    {
        if (!$this->nextFormUrl)
            return;
        $nextFormUrl = call_user_func($this->nextFormUrl, $this->modelObject);
        return \Yii::createObject(ModalWindow::class, [])
            ->reloadContainer($this->container)
            ->info($this->message, ModalWindow::TYPE_OK)
            ->runFunction("showForm('{$nextFormUrl}')")
            ->run();
    }

    protected function loadNext()
    {
        $classname = $this->model;
        $nextObjectId = $classname::find()
            ->where("id = (SELECT MIN(id) FROM {$classname::tableName()} WHERE id>{$this->modelObject->id} )")
            ->limit(1)
            ->select('id')
            ->scalar();
        if (empty($nextObjectId))
            return $this->getSuccessReturnString();
        $currentPath = Yii::$app->request->getPathInfo();
        return \Yii::createObject(ModalWindow::class, [])
            ->reloadContainer($this->container)
            ->info($this->message, ModalWindow::TYPE_OK)
            ->runFunction("showForm('{$currentPath}',{$nextObjectId})")
            ->run();
    }

    protected function getSuccessReturnString()
    {
        if ($this->successHtml)
            $this->returnString = call_user_func($this->successHtml, $this->modelObject);
        elseif ($this->successJs)
            $this->returnString = Html::tag('script', $this->successJs);
        else
            $this->returnString = \Yii::createObject(ModalWindow::class, [])
                ->reloadContainer($this->container)
                ->info($this->message, ModalWindow::TYPE_OK)
                ->hide()
                ->run();
        return $this->returnString;
    }

}
