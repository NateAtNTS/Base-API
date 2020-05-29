<?php

namespace baseapi\controllers;

use BaseApi;
use yii\filters\auth\HttpBearerAuth;
use baseapi\models\User;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class UserController extends \yii\web\Controller
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
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Returns a list of users in JSON format
     *
     * @return array
     */
    public function actionIndex()
    {
        return [
            "users" => User::getUsers(),
        ];
    }

    public function actionUpdate() {
        $user = new User();
        if ($user->load(BaseApi::$app->request->post(), '') && $user->updateUser()) {
            return ["success" => true];
        } else {
            return ["success" => false];
        }
    }


    public function actionAdd()
    {
        $user = new User();

        if ($user->load(BaseApi::$app->request->post(), '') && $user->add()) {
            return ["success" => true];
        } else {
            return ["success" => false];
        }
    }


    public function actionCheckUsername()
    {
        $bUsernameExists = User::find()
            ->where(["username" => BaseApi::$app->request->post("username", '')])
            ->count();

        if ($bUsernameExists > 0) {
            return ["usernameUnique" => false];
        }

        return ["usernameUnique" => true];
    }

} // class
