<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace baseapi\console\controllers;

use BaseApi;
use baseapi\console\Controller;
use baseapi\migrations\Install;
use Seld\CliPrompt\CliPrompt;
use yii\console\ExitCode;
use yii\db\config;
use yii\helpers\Console;


class InstallController extends Controller
{

    public $isWorkingDbConnection;

    public $username;

    public $firstName;

    public $lastName;

    public $emailAddress;

    public $password;


    public $defaultAction = 'api';

    /**
     * Installs The Base API
     * @param bool $bUseBaseInstallMigration
     *
     * @return int Exit code
     */
    public function actionApi($bUseBaseInstallMigration=true)
    {
        // check to see if the database connection is working.
        $this->getIsDbConnectionValid();

        // proceed or exit depending on whether the API has been installed yet.
        if (BaseApi::$app->getIsInstalled()) {
            BaseApi::_console("Base API is already installed.");
            return ExitCode::OK;
        }

        $this->getDefaultUserInfo();
        BaseApi::_console("Installing...");

        /**
         * Most projects will not use the base install migration
         */
        if ($bUseBaseInstallMigration) {
            $installMigration = new Install([
                'username' => $this->username,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'password' => $this->password,
                'admin' => 'S',
                'email' => $this->emailAddress,
            ]);
            $result = $installMigration->safeUp();

            if ($result) {
                BaseApi::_console("Installation Done! ");
            } else {
                BaseApi::_console("Installation Failed!");
            }
            return ExitCode::OK;
        }
    }

    /**
     * This is only available in dev mode
     */
    public function actionUninstall() {
        $uninstallMigration = new Install();

        $result = $uninstallMigration->safeDown();

        if ($result) {
            BaseApi::_console("Removal Done! ");
        } else {
            BaseApi::_console("Removal Failed!");
        }
    }

    /**
     * Returns whether the DB connection settings are valid
     */
    public function getIsDbConnectionValid()
    {
        try {
            BaseApi::$app->db->open();
            $this->isWorkingDbConnection = true;
        } catch (yii\db\Exception $e) {
            BaseApi::_console("There was a problem connecting to the database.");
            $this->isWorkingDbConnection = true;
        }
    }

    public function getDefaultUserInfo()
    {
        $this->username = $this->prompt('Username (lowercase letters, numbers and no spaces):', ['default' => 'admin', "validator" => [$this, 'validateUsername']]);
        $this->firstName = $this->prompt("First Name:", ['required' => true, "validator" => [$this, 'validateName']]);
        $this->lastName = $this->prompt("Last Name:", ['required' => true, "validator" => [$this, 'validateName']]);
        $this->password = $this->_passwordPrompt();
        $this->emailAddress = $this->prompt("Email Address:", ['required' => true, "validator" =>[$this, 'validateEmail']]);

    }

    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validateEmail(string $value, string &$error = null): bool
    {
        if (! preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $value)) {
            $error = "Please enter a valid email!";
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validateUsername(string $value, string &$error = null): bool
    {
        if (! preg_match('/^[a-z0-9\']+$/', $value)) {
            $error = "Username must be all lowercase and no spaces!";
            return false;
        }
        return true;
    }


    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validateName(string $value, string &$error = null): bool
    {
        if (! preg_match('/^[a-zA-Z]+$/', $value)) {
            $error = "Upper or Lowercase Letters Only!";
            return false;
        }
        return true;
    }

    /**
     * @param string $value
     * @param string|null $error
     * @return bool
     */
    public function validatePassword(string $value, string &$error = null): bool
    {
        if (! preg_match('/^(?=.*[A-Z].*[A-Z])(?=.*[!@#$&*])(?=.*[0-9].*[0-9])(?=.*[a-z].*[a-z].*[a-z]).{8,}$/', $value)) {
            $error = "Please enter a valid password!";
            return false;
        }
        return true;
    }

    /**
     * I borrowed this code from CraftCMS
     *
     * @return string
     */
    public function _passwordPrompt(): string
    {
        top:

        Console::output("Password Requirements:");
        Console::output("- At least 8 characters long");
        Console::output("- At least one of the following: ! @ # $ &");
        Console::output("- At least two uppercase letters");
        Console::output("- At least three lowercase letters");
        Console::output("- At least two digits");
        $this->stdout('Password: ');
        if (($password = CliPrompt::hiddenPrompt(true)) === '') {

            $this->stdout('Invalid input.' . PHP_EOL);
            goto top;
        }
        if (!$this->validatePassword($password, $error)) {
            Console::output(PHP_EOL ."Please Enter a valid password!");
            goto top;
        }
        $this->stdout('Confirm: ');
        if (!($matched = ($password === CliPrompt::hiddenPrompt(true)))) {
            $this->stdout('Passwords didn\'t match, try again.' . PHP_EOL, Console::FG_RED);
            goto top;
        }
        return $password;
    }





}
