<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:04
 */

namespace Exaprint\TCPDF;


class Cell implements Element
{

    const ALIGN_CENTER  = 'C';
    const ALIGN_LEFT    = 'L';
    const ALIGN_JUSTIFY = 'J';
    const ALIGN_RIGHT   = 'R';

    const BORDER_NO_BORDER = 0;
    const BORDER_FRAME     = 1;

    const CALIGN_CELL_TOP      = 'T';
    const CALIGN_CENTER        = 'C';
    const CALIGN_CELL_BOTTOM   = 'B';
    const CALIGN_FONT_TOP      = 'A';
    const CALIGN_FONT_BASELINE = 'L';
    const CALIGN_FONT_BOTTOM   = 'D';

    const VALIGN_TOP    = 'T';
    const VALIGN_CENTER = 'C';
    const VALIGN_BOTTOM = 'B';

    /** @var Position */
    public $position;

    /** @var Font */
    public $font;

    /** @var  FillColor */
    public $fillColor;

    /** @var  TextColor */
    public $textColor;

    /** @var  CellPadding */
    public $cellPadding;

    public $lineStyle;

    public $width;

    public $height = 0;

    public $text = '';

    public $fill = false;

    public $border = 0;

    public $align = "";

    public $stretch;

    public $cAlign = 'T';

    public $vAlign = 'M';

    public $ln = 0;

    public $link = '';

    public $ignoreMinHeight = true;

    public function __construct()
    {
    }

    public function draw(\TCPDF $pdf)
    {
        if ($this->font) $this->font->apply($pdf);
        if ($this->position) $this->position->apply($pdf);
        if ($this->textColor) $this->textColor->apply($pdf);
        if ($this->fillColor) $this->fillColor->apply($pdf);
        if ($this->cellPadding) $this->cellPadding->apply($pdf);

        $pdf->Cell(
            $this->width,
            $this->height,
            $this->text,
            $this->border,
            $this->ln,
            $this->align,
            $this->fill,
            $this->link,
            $this->stretch,
            $this->ignoreMinHeight,
            $this->cAlign,
            $this->vAlign
        );

        if ($this->font) $this->font->revert($pdf);
        if ($this->position) $this->position->revert($pdf);
        if ($this->textColor) $this->textColor->revert($pdf);
        if ($this->fillColor) $this->fillColor->revert($pdf);
        if ($this->cellPadding) $this->cellPadding->revert($pdf);

    }

} 