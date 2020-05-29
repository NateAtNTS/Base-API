<?php

use yii\helpers\VarDumper;

class BaseApi extends Yii
{
    public static $app;

    /**
     * Helper function that should be turned into a trait once I can learn how to use them.
     *
     * @param $text
     */
    public static function _console($text) {
        echo PHP_EOL . $text . PHP_EOL . PHP_EOL;
    }


    public static function dd($var, int $depth = 10, bool $highlight = true)
    {
        VarDumper::dump($var, $depth, $highlight);
        exit();
    }


}