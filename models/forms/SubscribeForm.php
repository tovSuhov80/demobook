<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class SubscribeForm extends Model
{
    public array $authorNames;
    public $phone;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['authorNames', 'phone'], 'required'],
            [['authorNames'], 'each', 'rule' => ['string']],
            [['phone'], 'string', 'min' => 10, 'max' => 15],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('app', 'Номер телефона'),
            'verifyCode' => Yii::t('app', 'Проверочный код'),
        ];
    }


}
