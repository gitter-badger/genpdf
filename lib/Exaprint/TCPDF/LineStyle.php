<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/02/2014
 * Time: 09:13
 */

namespace Exaprint\TCPDF;


class LineStyle implements DrawingContext {

    const JOIN_MITER = 'miter';
    const JOIN_BEVEL = 'bevel';
    const JOIN_ROUND = 'round';

    public $width;

    public $cap;

    public $join;

    public $dash;

    public $phase;

    /**
     * @var Color
     */
    public $color;


    public function apply(\TCPDF $pdf)
    {
        $style = [];

        if($this->width) $style['width'] = $this->width;
        if($this->cap) $style['cap'] = $this->cap;
        if($this->join) $style['join'] = $this->join;
        if($this->dash) $style['dash'] = $this->dash;
        if($this->phase) $style['phase'] = $this->dash;
        if($this->color) $style['color'] = $this->color->toArray();

        $pdf->SetLineStyle($style);
    }

    public function revert(\TCPDF $pdf)
    {
        $lineStyle = new self();
        $lineStyle->color = Color::greyscale(0);
        $lineStyle->dash = 0;
        $lineStyle->width = 0.25;
        $lineStyle->apply($pdf);
    }


} 