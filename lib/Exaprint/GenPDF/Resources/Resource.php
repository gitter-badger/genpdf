<?php
/**
 * Created by PhpStorm.
 * User: lucien
 * Date: 04/11/13
 * Time: 17:09
 */

namespace Exaprint\GenPDF\Resources;


class Resource {

    protected $_imageFolder;

    function __construtor() {
        $this->_imageFolder = $_SERVER["SERVER_NAME"] . "/static/assets/";
    }

} 