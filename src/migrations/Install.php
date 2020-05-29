<?php

namespace BaseApi\migrations;

use baseapi\models\User;
use yii\db\Migration;

class Install extends Migration
{
    /**
     * @var string The admin's username
     */
    public $username;

    /**
     * @var string The admin's first name
     */
    public $firstName;

    /**
     * @var string The admin's last name
     */
    public $lastName;

    /**
     * @var string The admin's password
     */
    public $password;

    /**
     * @var bool The admin is an admin!
     */
    public $admin;

    /**
     * @var string The admin's email
     */
    public $email;

    /**
     * Install constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        // hide the database output from the screen.
        $this->compact = true;
    }

    /**
     * @return bool
     */
    public function safeUp()
    {
        $this->createInfo();
        $this->createUsers();
        return true;
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        if (getenv('ENVIRONMENT') == "dev") {
            $this->dropTable("{{%info}}");
            $this->dropTable("{{%users}}");
            return true;
        } else {
            echo PHP_EOL . PHP_EOL . PHP_EOL . "*** Uninstalling is only available in the dev environment." . PHP_EOL . PHP_EOL . PHP_EOL;
            return false;
        }
    }

    /**
     * Create the Info Table and Populate it
     */
    private function createInfo()
    {
        $this->createTable("{{%info}}", [
            'id' => $this->primaryKey(),
            'version' => $this->string()->notNull(),
            'dateCreated' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
            'dateUpdated' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
        ]);

        $info = [
            "version" => "1.0"
        ];

        $this->insert("{{%info}}", $info);
    }

    /**
     * Create the Users Table and Populate it
     */
    private function createUsers()
    {
        $this->createTable("{{%users}}", [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(100),
            'lastName' => $this->string(100),
            'username' => $this->string(100)->notNull(),
            'email' => $this->string(100),
            'password' => $this->string(),
            'admin' => "ENUM('Y','N','S') DEFAULT 'N'",
            'authKey' => $this->char(255),
            'dateCreated' => $this->dateTime()->defaultExpression("CURRENT_TIMESTAMP"),
            'dateUpdated' => $this->dateTime()->defaultExpression("CURRENT_TIMESTAMP"),
            'photoId' => $this->integer(),
        ]);

        $newUser =  new User ([
            "username" => $this->username,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "newPassword" => $this->password,
            "admin" => $this->admin,
            "email" => $this->email,
        ]);

        $newUser->add();
    }

}