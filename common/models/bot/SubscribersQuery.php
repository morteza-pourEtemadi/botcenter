<?php

namespace common\models\bot;

/**
 * This is the ActiveQuery class for [[Subscribers]].
 *
 * @see Subscribers
 */
class SubscribersQuery extends \yii\redis\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Subscribers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Subscribers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
