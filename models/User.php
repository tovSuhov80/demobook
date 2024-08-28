<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function behaviors(): array
    {
        return [
                [
                    'class' => TimestampBehavior::class,
                    'value' => new Expression('NOW()'),
                ]
        ];
    }

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 3, 'max' => 128],
            ['username', 'unique'],

            ['password_hash', 'required'],
            ['password_hash', 'string', 'max' => 64],

            ['auth_key', 'required'],
            ['auth_key', 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|\yii\web\IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public static function findIdentityByAccessToken($token, $type = null): ?\yii\web\IdentityInterface
    {
        throw new Exception('[findIdentityByAccessToken] is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username): null|static
    {
        return static::findOne(['username' => $username]);
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword(string $password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    public function setPasswordHash(string $password): self
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = \Yii::$app->security->generateRandomString(64);
    }

    public function getId()
    {
        return $this->id;
    }
}
