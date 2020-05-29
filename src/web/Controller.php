<?php

namespace baseapi\web;

use BaseApi;
use yii\rest\Controller as RestController;

abstract class Controller extends RestController
{

    public function runAction($id, $params = [])
    {

        if (! BaseApi::$app->getIsInstalled())  {

            /**
             * TODO: JSON response that the API has not been installed
             */
        }

        return parent::runAction($id, $params);
    }

}