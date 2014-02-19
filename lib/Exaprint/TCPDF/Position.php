<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:10
 */

namespace Exaprint\TCPDF;


class Position implements DrawingContext {

    public $x;

    public $y;

    /** @var Position */
    protected $previousPosition;

    function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function apply(\TCPDF $pdf)
    {
        $this->previousPosition = new self($pdf->GetX(), $pdf->GetY());
        $pdf->SetXY($this->x, $this->y);
    }

    public function revert(\TCPDF $pdf)
    {
        $this->previousPosition->apply($pdf);
    }


}