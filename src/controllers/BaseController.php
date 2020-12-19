<?php

namespace baseapi\controllers;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class BaseController extends \yii\web\Controller
{
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        if (getenv("BEARER_AUTH") == "ON") {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
            ];
        }
        return $behaviors;
    }
} // class
