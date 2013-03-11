<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 05/02/13
 * Time: 00:06
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources\DAO;


class Database extends \PDO {

    protected $_settings = [
        "prod" => [
            "server" => "192.168.3.1",
            "dbname" => "Exa_Back",
            "username" => "exa",
            "password" => "exa%2012",
            "port" => "1433",
        ],

        "isoprod" => [
            "server" => "192.168.3.21",
            "dbname" => "Exa_Back_IsoProd",
            "username" => "exa",
            "password" => "exa%2012",
            "port" => "1433",
        ],

        "test" => [
            "server" => "192.168.3.11",
            "dbname" => "Exa_Back_Test",
            "username" => "exa",
            "password" => "exa%2012",
            "port" => "1433",
        ],

        "dev" => [
            "server" => "192.168.3.11",
            "dbname" => "Exa_Back_Dev",
            "username" => "exa",
            "password" => "exa%2012",
            "port" => "1433",
        ],
    ];

    public function __construct($env = "prod")
    {
        $settings = (object) $this->_settings[$env];
        $dsn = "dblib:host=$settings->server:$settings->port;dbname=$settings->dbname;charset=UTF-8";
        parent::__construct($dsn, $settings->username, $settings->password);


    }
}