<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:22
 */

namespace Exaprint\TCPDF;


class FillColor implements DrawingContext {

    /** @var  Color */
    public $color;

    function __construct($color)
    {
        $this->color = $color;
    }


    public function apply(\TCPDF $pdf)
    {
        $pdf->SetFillColor(
            $this->color->col1,
            $this->color->col2,
            $this->color->col3,
            $this->color->col4,
            $this->color->ret,
            $this->color->name
        );
    }

    public function revert(\TCPDF $pdf)
    {
        $pdf->SetFillColor();
    }


} 