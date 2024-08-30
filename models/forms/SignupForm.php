<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $password;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => Yii::t('app','Поле не должно быть пустым')],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => Yii::t('app','Пользователь с таким логином уже зарегистрирован')],
            ['username', 'string', 'min' => 3, 'max' => 128],
            ['password', 'required', 'message' => Yii::t('app','Поле не должно быть пустым')],
            ['password', 'string', 'min' => 6, 'message' => Yii::t('app', 'Пароль должен содержать минимум 6 символов')],
            ['verifyCode', 'captcha'],

        ];
    }

    public function signupValidate(): bool
    {
        return $this->validate();
    }
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Логин'),
            'password' => Yii::t('app', 'Пароль'),
            'verifyCode' => Yii::t('app', 'Проверочный код'),
        ];
    }
}