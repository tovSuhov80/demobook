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
            [['authorNames', 'phone'], 'required'],
            ['authorNames', 'validateAuthorNames'],
            [['authorNames'], 'each', 'rule' => ['string']],
            [['phone'], 'string', 'min' => 10, 'max' => 15],
            ['verifyCode', 'captcha'],
        ];
    }

    public function validateAuthorNames($attribute, $params)
    {
        $hasError = true;
        if (!empty($this->$attribute) && is_array($this->$attribute)) {
            foreach ($this->$attribute as $value) {
                if (!empty($value)) {
                    $hasError = false;
                    break;
                }
            }
        }

        if ($hasError) {
            $this->addError($attribute, 'Необходимо отметить хотя бы одного автора.');
        }
    }

    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('app', 'Номер телефона'),
            'verifyCode' => Yii::t('app', 'Проверочный код'),
        ];
    }


}
