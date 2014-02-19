<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 12:00
 */

namespace Exaprint\TCPDF;


interface Element {

    public function draw(\TCPDF $pdf);
} 