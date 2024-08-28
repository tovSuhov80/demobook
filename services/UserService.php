<?php

namespace app\services;

use app\models\LoginForm;
use app\models\User;
use Exception;
use Yii;
use yii\helpers\VarDumper;

class UserService
{
    /**
     * Создание нового пользователя.
     *
     * @param string $username
     * @param string $password
     * @return User|null
     * @throws Exception
     */
    public function createUser(string $username, string $password): ?User
    {
        $user = new User();
        $user->username = $username;
        $user->setPasswordHash($password);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }


    public function loginUser(string $username, bool $rememberMe): bool
    {
        return Yii::$app->user->login(User::findByUsername($username), $rememberMe ? 3600*24*30 : 0);
    }
}