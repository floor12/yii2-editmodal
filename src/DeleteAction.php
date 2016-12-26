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
use \Yii;

class DeleteAction extends Action
{
    public $model;
    public $message = 'Объект удален';

    public function run()
    {
        $id = \Yii::$app->request->getBodyParams('id');

        if (!$id)
            $model = new $this->model;
        else {
            $classname = $this->model;
            $model = $classname::findOne($id);
            if (!$model)
                throw new NotFoundHttpException("Object with id {$id} not found");
        }

        if ($model->delete())
            return $this->message;
        else
            throw new BadRequestHttpException('Unable to delete');
    }
}