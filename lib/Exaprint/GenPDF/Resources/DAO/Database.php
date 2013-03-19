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



    public function __construct($env = "prod")
    {
        $dsn = sprintf("dblib:host=%s:1433;dbname=%s;charset=UTF-8", $_SERVER['DB_HOST'], $_SERVER['DB_NAME']);
        parent::__construct($dsn, $_SERVER['DB_USER'], $_SERVER['DB_PASS']);
    }
}