<?php

namespace common\models\bot\botId_2;

use Yii;

/**
 * This is the model class for table "2_translate".
 *
 * @property integer $index
 * @property string $text
 */
class Translate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '2_translate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'index' => 'Index',
            'text' => 'Text',
        ];
    }
}
