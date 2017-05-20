<?php

namespace common\models\bot;

/**
 * This is the ActiveQuery class for [[Receipt]].
 *
 * @see Receipt
 */
class ReceiptQuery extends \yii\redis\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Users[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Users|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
