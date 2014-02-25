<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24/02/2014
 * Time: 14:47
 */

namespace Exaprint\TCPDF;

class RoundedRect extends Rect {

    public $radius;
    public $corners;

    public $topRight = 1;
    public $bottomRight = 1;
    public $bottomLeft= 1;
    public $topLeft = 1;

    public function draw(\TCPDF $pdf)
    {

        if ($this->fillColor)
            $this->fillColor->apply($pdf);

        $pdf->RoundedRect(
            $this->position->x,
            $this->position->y,
            $this->dimensions->width,
            $this->dimensions->height,
            $this->radius,
            [$this->topRight, $this->bottomRight, $this->bottomLeft, $this->topLeft],
            $this->style
        );

        if ($this->fillColor)
            $this->fillColor->revert($pdf);
    }


} 