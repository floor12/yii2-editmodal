<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 02.07.2018
 * Time: 10:13
 */

namespace floor12\editmodal;

use yii\grid\Column;

/** This class helps to add edit buttons column to GridView
 * Class EditModalColumn
 * @package floor12\editmodal
 */
class EditModalColumn extends Column
{

    public $showCopy = false;
    public $showDelete = true;
    public $showEdit = true;
    public $editPath = 'form';
    public $deletePath = 'delete';
    public $container = '#items';
    public $cssClass = "btn btn-default btn-sm";

    public $contentOptions = ['style' => 'white-space: nowrap; text-align:right;'];

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $buttons = [];

        if ($this->showBtn($model, 'showEdit')) {
            $buttons[] = EditModalHelper::editBtn($this->editPath, $model->id, $this->cssClass);
        }

        if ($this->showBtn($model, 'showCopy')) {
            $buttons[] = EditModalHelper::CopyBtn($this->editPath, $model->id, $this->cssClass);
        }

        if ($this->showBtn($model, 'showDelete')) {
            $buttons[] = EditModalHelper::deleteBtn($this->deletePath, $model->id, $this->cssClass, $this->container);
        }

        return implode(' ', $buttons);
    }

    protected function showBtn($model, $fieldName)
    {
        return
            (is_callable($this->$fieldName) && call_user_func($this->$fieldName, $model)) ||
            (!is_callable($this->$fieldName) && $this->$fieldName);
    }
}