<?php

namespace common\models\site;

/**
 * This is the ActiveQuery class for [[Box]].
 *
 * @see Box
 */
class BoxQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Box[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Box|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
