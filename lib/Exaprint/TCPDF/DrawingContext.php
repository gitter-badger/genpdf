<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:09
 */

namespace Exaprint\TCPDF;


interface DrawingContext {

    public function apply(\TCPDF $pdf);

    public function revert(\TCPDF $pdf);
} 