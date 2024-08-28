<?php

namespace app\components;

use yii\web\IdentityInterface;

class EmptyIdentify implements IdentityInterface
{
    public static function findIdentity($id)
    {
        // Возвращаем пустой объект, если идентификатор совпадает
        return $id === 1 ? new self() : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Возвращаем пустой объект, если токен совпадает
        return $token === 'yD2=qao8]V1f0%.Zq>3cH~f}F@wM:8GA#v}w6iT5oeDCikkgM2YZjL#E*=D0UM]f' ? new self() : null;
    }

    public function getId()
    {
        // Возвращаем идентификатор пользователя
        return 1;
    }

    public function getAuthKey()
    {
        // Возвращаем ключ аутентификации
        return 'dummy-auth-key';
    }

    public function validateAuthKey($authKey)
    {
        // Проверяем, совпадает ли ключ аутентификации
        return $authKey === 'dummy-auth-key';
    }
}