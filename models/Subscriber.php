<?php

namespace app\models;

use Yii;

/**
 *
 * @property int $id
 * @property string $phone
 * @property int $author_id
 */
class Subscriber extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%subscriptions}}';
    }

    public function rules()
    {
        return [
            [['phone', 'author_id'], 'required'],
            [['phone'], 'unique', 'targetAttribute' => ['phone', 'author_id']],
            [['phone'], 'string', 'max' => 16],
            [['author_id'], 'integer', 'min' => 1],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'phone' => Yii::t('app', 'Номер телефона'),
            'author_id' => Yii::t('app','Автор'),
        ];
    }
}
