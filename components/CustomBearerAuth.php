<?php

namespace app\components;

use Yii;
use yii\filters\auth\HttpBearerAuth;

class CustomBearerAuth extends HttpBearerAuth
{
    public function authenticate($user, $request, $response)
    {
        $headers = $request->getHeaders();
        $authorizationHeader = $headers->get('Authorization');

        if (preg_match('/^Bearer\s+(.*?)$/i', $authorizationHeader, $matches)) {
            $token = $matches[1];

           return EmptyIdentify::findIdentityByAccessToken($token);
        }

        return null; // Токен не валидный
    }

    public function handleFailure($response)
    {
        $response->statusCode = 403;
        $response->data = [
            'status' => 'error',
            'code' => '403',
            'message' => 'Invalid token',
        ];
        $response->send();
        Yii::$app->end();
    }
}