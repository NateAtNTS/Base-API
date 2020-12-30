<?php

namespace baseapi\helpers;

use BaseApi;

Class UserHelper {

    /**
     * Check to see if the logged in user is an admin
     */
    public static function isAdmin() {

        if (BaseApi::$app->user->identity->admin != "N") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the user id of the person logged in
     *
     * @return int
     */
    public static function userId()
    {
        return BaseApi::$app->user->identity->id;
    }

}



