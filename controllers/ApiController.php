<?php

namespace app\controllers;

use app\components\CustomBearerAuth;
use yii\web\Controller;
use yii\web\Response;
use Yii;


class ApiController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Добавляем кастомный фильтр Bearer авторизации
        $behaviors['authenticator'] = [
            'class' => CustomBearerAuth::class,
        ];

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actionIndex(): array
    {
        $method = \Yii::$app->request->post('method') ?:
            \Yii::$app->request->get('method');

        switch ($method) {
            case 'rates':
                return $this->actionRates();
            case 'convert':
                return $this->actionConvert();
            default:
                Yii::$app->response->statusCode = 400;
                return ['error' => 'Invalid method'];
        }
    }

    public function actionRates(): array
    {
        $currency = Yii::$app->request->get('currency');
        $currency_array = [];
        if(!empty($currency))
        {
            $currency_array = explode(",", $currency);
            $currency_array = array_flip($currency_array);
        }

        $rates_data = $this->getRates($currency_array);

        $rates_data = array_map(function($elem) {
            return $elem * 0.98; }, $rates_data);

        // Логика для получения курсов валют
        return [
            'status' => 'success',
            'code' => 200,
            'data' => $rates_data,
        ];
    }

    /**
     * @param array $currency_array
     * @return array
     */
    private function getRates(array $currency_array = []): array
    {
        $jsonFilepath = Yii::getAlias('@app/data/rates.json');
        $jsonRates = file_get_contents($jsonFilepath);
        $ratesArray = json_decode($jsonRates, true);

        $rates_data = [];

        foreach($ratesArray['data'] as $rate)
        {
            if((!empty($currency_array) && isset($currency_array[$rate['symbol']])) || empty($currency_array))
            {
                $rates_data[$rate['symbol']] = $rate['rateUsd'];
            }
        }

        asort($rates_data, SORT_NUMERIC);

        return $rates_data;
    }

    public function actionConvert(): array
    {
        $currency_from = Yii::$app->request->post('currency_from');
        $currency_to = Yii::$app->request->post('currency_to');
        $value = Yii::$app->request->post('value', 1);

        $rates = $this->getRates();

        if(!isset($rates[$currency_from]) || !isset($rates[$currency_to]))
        {
            return [
                'status' => 'error',
                'code' => '406',
                'message' => 'currency_from or currency_to are empty in rates'
            ];
        }


        if ($currency_from != 'USD') {
            // Рассчитываем значение в USD
            $value_in_usd = $value * $rates[$currency_from];
        }
        else
        {
            $value_in_usd = $value;
        }

        if($value_in_usd < 0.01)
        {
            return [
                'status' => 'error',
                'code' => '406',
                'message' => 'very small value currency_from'
            ];
        }


        // Применяем комиссию в 2%
        $value_in_usd_with_fee = $value_in_usd * 0.98; // 2% комиссия

        // Конвертируем в целевую валюту
        $converted_value = $value_in_usd_with_fee / $rates[$currency_to];

        if($currency_from == 'USD' && $currency_to == 'BTC')
        {
            $converted_value = round($converted_value, 10);
        }
        elseif($currency_from == 'BTC' && $currency_to == 'USD')
        {
            $converted_value = round($converted_value, 2);
        }

        return [
            'status' => 'success',
            'code' => 200,
            'data' => [
                'currency_from' => $currency_from,
                'to' => $currency_to,
                'value' => $value,
                'converted_value' => $converted_value,
            ],
        ];
    }
}