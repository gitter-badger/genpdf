<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 09:56
 */

namespace Exaprint\TCPDF;


class CellHeightRatio implements DrawingContext
{

    public $ratio = 1;

    protected $previousValue;

    function __construct($ratio = 1)
    {
        $this->ratio = $ratio;
    }

    public function apply(\TCPDF $pdf)
    {
        $this->previousValue = $pdf->getCellHeightRatio();
        $pdf->setCellHeightRatio($this->ratio);
    }

    public function revert(\TCPDF $pdf)
    {
        $pdf->setCellHeightRatio($this->previousValue);
    }


} 