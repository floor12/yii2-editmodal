<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.09.2018
 * Time: 20:16
 */

namespace floor12\editmodal;

use yii\data\DataProviderInterface;

interface FilterModelInterface
{
    /**
     * @return DataProviderInterface
     */
    public function dataProvider();

    /**
     * @param array $data
     * @param null $formName
     * @return mixed
     */
    public function load(array $data, $formName = null);
}