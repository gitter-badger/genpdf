<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/02/2014
 * Time: 16:51
 */

namespace Exaprint\TCPDF;


class Dimensions
{

    public $width;

    public $height;

    function __construct($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }

    function ratio()
    {
        return $this->width / $this->height;
    }
} 