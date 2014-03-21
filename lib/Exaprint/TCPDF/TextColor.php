<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:17
 */

namespace Exaprint\TCPDF;


class TextColor implements DrawingContext
{

    public $color;

    function __construct(Color $color)
    {
        $this->color = $color;
    }


    public function apply(\TCPDF $pdf)
    {
        $pdf->SetTextColor(
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
        $pdf->SetTextColor(0);
    }
} 