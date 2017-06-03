<?php

namespace common\models\bot\botId_2;

use common\components\RedisActiveRecord;

/**
 * Class Quran
 * @package common\models\botId_2
 *
 * @property integer id
 * @property integer index
 * @property string sura
 * @property integer suraNum
 * @property integer aya
 * @property string text
 * @property string translation
 * @property integer page
 */
class Quran extends RedisActiveRecord
{
    public function attributes()
    {
        return [
            'id',
            'index',
            'sura',
            'suraNum',
            'aya',
            'text',
            'translation',
            'page',
        ];
    }

    public function rules()
    {
        return [
            [['id', 'index', 'suraNum', 'aya', 'page'], 'integer'],
            [['sura', 'text', 'translation'], 'string'],
        ];
    }
}
