<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26.12.2016
 * Time: 11:31
 */

namespace floor12\editmodal;


use yii\base\Action;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;

class DeleteAction extends Action
{
    public $model;
    public $message = 'Объект удален';
    public $logic;
    public $access = true;


    public function run()
    {
        if (!$this->access)
            throw new ForbiddenHttpException();

        $params = \Yii::$app->request->getBodyParams();

        if (isset($params['id']) && $params['id']) {
            $classname = $this->model;
            $model = $classname::findOne(intval($params['id']));
            if (!$model)
                throw new NotFoundHttpException("Object with id {$params['id']} not found");
        } else
            $model = new $this->model;

        if ($this->logic) {
            if (\Yii::createObject($this->logic, [$model, \Yii::$app->user->identity])->execute())
                return $this->message;
        } else {
            if ($model->delete())
                return $this->message;
            else
                throw new BadRequestHttpException('Unable to delete');
        }


    }
}