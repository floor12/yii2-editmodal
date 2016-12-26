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

class EditModalAction extends Action
{
    public $model;
    public $message = 'Объект сохранен';
    public $view = '_form';

    public function run($id)
    {
        if (!$id)
            $model = new $this->model;
        else {
            $classname = $this->model;
            $model = $classname::findOne($id);
            if (!$model)
                throw new NotFoundHttpException("Object with id {$id} not found");
        }

        if (($model->load(Yii::$app->request->post())) && $model->save()) {
            return "<script>
                hideFormModal(); 
                $.pjax.reload({container:\"#items\"});
                info('{$this->message}', 1)
            </script>";
        }

        return $this->controller->renderAjax($this->view, ['model' => $model]);
    }
}