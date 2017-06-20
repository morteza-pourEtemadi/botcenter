<?php

namespace common\models\bot\botId_2;

use Yii;

/**
 * This is the model class for table "narratives".
 *
 * @property integer $id
 * @property string $quotee
 * @property string $quote
 * @property string $quoteeTranslation
 * @property string $quoteTranslation
 * @property string $source
 */
class Narratives extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'narratives';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quotee', 'quote', 'quoteeTranslation', 'quoteTranslation', 'source'], 'required'],
            [['quote', 'quoteTranslation'], 'string'],
            [['quotee', 'quoteeTranslation', 'source'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotee' => 'Quotee',
            'quote' => 'Quote',
            'quoteeTranslation' => 'Quotee Translation',
            'quoteTranslation' => 'Quote Translation',
            'source' => 'Source',
        ];
    }
}
