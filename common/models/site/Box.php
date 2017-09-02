<?php

namespace common\models\site;

use Yii;

/**
 * This is the model class for table "{{%box}}".
 *
 * @property integer $id
 * @property integer $size
 * @property string $title
 * @property integer $sid
 *
 * @property Category[] $categories
 */
class Box extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%box}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size', 'title', 'sid'], 'required'],
            [['size', 'sid'], 'integer'],
            [['title'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'size' => Yii::t('app', 'Size'),
            'title' => Yii::t('app', 'Title'),
            'sid' => Yii::t('app', 'Sid'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['box_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BoxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BoxQuery(get_called_class());
    }
}
