<?php

namespace baseapi\models;

use BaseApi;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public $newPassword;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstName','lastName','email'], 'required'],
            ['admin','default', 'value' => 'N'],
            ['password','default', 'value' => ''],
            ['username','default', 'value' => ''],
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['authKey' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public function add()
    {
        $this->password = BaseApi::$app->getSecurity()->generatePasswordHash($this->newPassword, 13);
        $this->authKey = BaseApi::$app->getSecurity()->generateRandomString();

        if (($this->password == '') || ($this->username == '')) {
            return false;
        }

        if ($this->validate()) {
            $this->save();

            return true;
        } else {
            return false;
        }
    }

    public function updateUser()
    {
        if ($this->validate()) {

            $updatedUser = BaseApi::$app->request->post();

            if (isset($updatedUser['password'])) {
                $updatedUser['password'] = BaseApi::$app->getSecurity()->generatePasswordHash($updatedUser['password']);
            }

            $user = static::findOne(BaseApi::$app->request->post("id"));
            $user->attributes = $updatedUser;
            $user->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a list of users without the password / token information
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getUsers() {
        return static::find()
            ->select(['id', 'firstName','lastName', 'username', 'email', 'admin'])
            ->all();
    }
}