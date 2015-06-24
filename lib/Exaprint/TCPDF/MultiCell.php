<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:04
 */

namespace Exaprint\TCPDF;


class MultiCell
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
    const VALIGN_MIDDLE = 'M';
    const VALIGN_BOTTOM = 'B';


    /** @var Font */
    public $font;

    /** @var FillColor */
    public $fillColor;

    /** @var TextColor */
    public $textColor;

    /** @var CellHeightRatio */
    public $cellHeightRatio;

    public $width;

    public $height = 0;

    public $x = 0;

    public $y = 0;

    public $text = '';

    public $fill = false;

    public $border = 0;

    public $align = "";

    public $stretch;

    public $cAlign = 'T';

    public $vAlign = 'M';

    public $ln = 0;

    public $reseth = true;

    public $isHtml = false;

    public $autoPadding = true;

    public $maxHeight = 0;

    public $fitCell = false;

    public function draw(\TCPDF $pdf)
    {
        if ($this->font) $this->font->apply($pdf);
        if ($this->textColor) $this->textColor->apply($pdf);
        if ($this->fillColor) $this->fillColor->apply($pdf);
        if ($this->cellHeightRatio) $this->cellHeightRatio->apply($pdf);

        $pdf->MultiCell(
            $this->width,
            $this->height,
            $this->text,
            $this->border,
            $this->align,
            $this->fill,
            $this->ln,
            $this->x,
            $this->y,
            $this->reseth,
            $this->stretch,
            $this->isHtml,
            $this->autoPadding,
            $this->maxHeight,
            $this->vAlign,
            $this->fitCell
        );
    }

} 