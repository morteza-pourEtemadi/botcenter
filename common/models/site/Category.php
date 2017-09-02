<?php

namespace common\models\site;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $box_id
 * @property string $title
 * @property string $slug
 * @property integer $count
 * @property integer $type
 *
 * @property Box $box
 * @property Content[] $contents
 */
class Category extends \yii\db\ActiveRecord
{
    const TYPE_IMAGE = 0;
    const TYPE_SLIDE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['box_id', 'title', 'slug', 'count', 'type'], 'required'],
            [['box_id', 'count', 'type'], 'integer'],
            [['title'], 'string', 'max' => 25],
            [['slug'], 'string', 'max' => 20],
            [['box_id'], 'exist', 'skipOnError' => true, 'targetClass' => Box::className(), 'targetAttribute' => ['box_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'box_id' => Yii::t('app', 'Box ID'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),
            'count' => Yii::t('app', 'Count'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBox()
    {
        return $this->hasOne(Box::className(), ['id' => 'box_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::className(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}
