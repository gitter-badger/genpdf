<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:07
 */

namespace Exaprint\TCPDF;


class Font implements DrawingContext
{

    const STYLE_BOLD       = 'B';
    const STYLE_REGULAR    = '';
    const STYLE_ITALIC     = 'I';
    const STYLE_BOLDITALIC = 'BI';

    public $family;

    public $size;

    public $style;

    public $color;

    protected $previous = [];

    function __construct($family, $size = '', TextColor $color = null, $style = null)
    {
        $this->family = $family;
        $this->size   = $size;
        $this->style  = $style;
        $this->color  = $color;
    }

    public function apply(\TCPDF $pdf)
    {
        $this->previous = [
            "family" => $pdf->getFontFamily(),
            "size" => $pdf->getFontSize(),
            "style" => $pdf->getFontStyle(),
        ];

        if ($this->color) $this->color->apply($pdf);

        $pdf->SetFont($this->family, $this->style, $this->size);
    }

    public function revert(\TCPDF $pdf)
    {
        $f = new self(
            $this->previous['family'],
            $this->previous['size'],
            new TextColor(Color::greyscale(0)),
            $this->previous['style']
        );

        $f->apply($pdf);
    }


} 