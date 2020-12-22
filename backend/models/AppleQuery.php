<?php
/**
 * Created by PhpStorm.
 * User: behruz
 * Date: 22.12.2020
 * Time: 14:08
 */

namespace backend\models;


class AppleQuery extends \yii\db\ActiveQuery
{
    public function fall()
    {
        return $this->andWhere('[[status]]=1');
    }

    /**
     * {@inheritdoc}
     * @return Apple[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Apple|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}