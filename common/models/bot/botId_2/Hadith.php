<?php

namespace common\models\bot\botId_2;

use common\components\RedisActiveRecord;

/**
 * Class User
 * @package common\models\botId_1
 *
 * @property integer id
 * @property integer index
 * @property string quoter
 * @property string quoter_trans
 * @property string quote
 * @property string quote_trans
 * @property string source
 */
class Hadith extends RedisActiveRecord
{
    public function attributes()
    {
        return [
            'id',
            'index',
            'quoter',
            'quoter_trans',
            'quote',
            'quote_trans',
            'source'
        ];
    }

    public function rules()
    {
        return [
            [['id', 'index'], 'integer'],
            [['quoter', 'quoter_trans', 'quote', 'quote_trans', 'source'], 'string'],
        ];
    }
}
