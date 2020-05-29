<?php

namespace baseapi\base;

use BaseApi;
use yii\db\config;


trait ApplicationTrait
{

    private $isApiInstalled;



    /**
     * Returns whether Craft is installed.
     *
     * @return bool
     */
    public function getIsInstalled(): bool
    {
        if ($this->isApiInstalled !== null) {
            return $this->isApiInstalled;
        }

        $infoTable = BaseApi::$app->db->schema->getTableSchema("{{%info}}");

        if ($infoTable === null) {
            $this->isApiInstalled = false;
        } else {
            $this->isApiInstalled = true;
        }

        return $this->isApiInstalled;

    }


}