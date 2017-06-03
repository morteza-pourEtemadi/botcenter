<?php

namespace common\models\bot\botId_2;

use Yii;

/**
 * This is the model class for table "2_text".
 *
 * @property integer $index
 * @property string $surah
 * @property integer $sura
 * @property integer $aya
 * @property string $text
 * @property integer $page
 */
class Text extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '2_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surah', 'text'], 'required'],
            [['sura', 'aya', 'page'], 'integer'],
            [['text'], 'string'],
            [['surah'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'index' => 'Index',
            'surah' => 'Surah',
            'sura' => 'Sura',
            'aya' => 'Aya',
            'text' => 'Text',
            'page' => 'Page',
        ];
    }
}
