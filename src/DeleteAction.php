<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 26.12.2016
 * Time: 11:31
 */

namespace floor12\editmodal;


use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DeleteAction extends Action
{
    public $model;
    public $message = 'Объект удален';
    public $logic;
    public $access = true;
    public $container = '#items';


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
                return json_encode(['message' => $this->message, 'container' => $this->container]);
        } else {
            if ($model->delete())
                return json_encode(['message' => $this->message, 'container' => $this->container]);
            else
                throw new BadRequestHttpException('Unable to delete');
        }


    }
}