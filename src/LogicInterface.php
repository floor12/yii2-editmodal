<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.09.2018
 * Time: 20:16
 */

namespace floor12\editmodal;


use yii\web\IdentityInterface;

interface LogicInterface
{
    /**
     * LogicInterface constructor.
     * @param $model
     * @param array $data
     * @param IdentityInterface $identity
     */
    public function __construct($model, array $data, IdentityInterface $identity);

    /**
     * @return bool
     */
    public function execute();
}