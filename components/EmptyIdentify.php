<?php

namespace app\components;

use yii\web\IdentityInterface;

class EmptyIdentify implements IdentityInterface
{
    public static function findIdentity($id)
    {
        // ���������� ������ ������, ���� ������������� ���������
        return $id === 1 ? new self() : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // ���������� ������ ������, ���� ����� ���������
        return $token === 'yD2=qao8]V1f0%.Zq>3cH~f}F@wM:8GA#v}w6iT5oeDCikkgM2YZjL#E*=D0UM]f' ? new self() : null;
    }

    public function getId()
    {
        // ���������� ������������� ������������
        return 1;
    }

    public function getAuthKey()
    {
        // ���������� ���� ��������������
        return 'dummy-auth-key';
    }

    public function validateAuthKey($authKey)
    {
        // ���������, ��������� �� ���� ��������������
        return $authKey === 'dummy-auth-key';
    }
}