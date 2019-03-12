<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.09.2018
 * Time: 20:16
 */

namespace floor12\editmodal;


use yii\db\ActiveRecordInterface;


interface LogicInterface
{
    /**
     * LogicInterface constructor.
     * @param ActiveRecordInterface $model
     * @param array $data
     */
    public function __construct(ActiveRecordInterface $model, array $data);

    /**
     * @return bool
     */
    public function execute();
}