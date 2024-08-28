<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;
use app\models\User;

class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Настройка Bearer авторизации
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        // Настройка формата ответа (JSON)
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $params = \Yii::$app->request->post();
        $user = User::findOne(['username' => $params['username']]);

        if ($user && \Yii::$app->security->validatePassword($params['password'], $user->password_hash)) {
            // Генерация и сохранение access_token
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->save();

            return [
                'access_token' => $user->access_token,
            ];
        }

        \Yii::$app->response->statusCode = 401;
        return ['message' => 'Invalid username or password'];
    }

    public function actionProfile()
    {
        $user = \Yii::$app->user->identity;
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ];
    }
}