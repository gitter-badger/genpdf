<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19/02/2014
 * Time: 11:07
 */

namespace Exaprint\TCPDF;


class Text implements Element {

    public $font;

    /**
     * @var CellPadding
     */
    public $cellPadding;
    public $x;
    public $y;
    public $txt;
    public $fstroke = false;
    public $fclip = false;
    public $ffill = true;
    public $border = 0;
    public $ln = 0;
    public $align = '';
    public $fill = false;
    public $link = '';
    public $stretch = 0;
    public $ignoreMinHeight = false;
    public $cAlign = '';
    public $vAlign = '';
    public $rtlOff = false;

    function __construct($x, $y, $txt, Font $font = null)
    {
        $this->txt = $txt;
        $this->x   = $x;
        $this->y   = $y;
        $this->font = $font;
    }


    public function draw(\TCPDF $pdf)
    {
        if($this->font) $this->font->apply($pdf);
        if($this->cellPadding) $this->cellPadding->apply($pdf);
        $pdf->Text(
            $this->x,
            $this->y,
            $this->txt,
            $this->fstroke,
            $this->fclip,
            $this->ffill,
            $this->border,
            $this->ln,
            $this->align,
            $this->fill,
            $this->link,
            $this->stretch,
            $this->ignoreMinHeight,
            $this->cAlign,
            $this->vAlign,
            $this->rtlOff
        );
        if($this->font) $this->font->revert($pdf);
        if($this->cellPadding) $this->cellPadding->revert($pdf);
    }


} 