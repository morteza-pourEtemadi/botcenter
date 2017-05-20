<?php

namespace common\models\bot;

/**
 * This is the ActiveQuery class for [[Bot]].
 *
 * @see Bot
 */
class BotQuery extends \yii\redis\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Bot[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Bot|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
