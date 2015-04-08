<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 15:04
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Planche;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\LineStyle;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Rect;
use Exaprint\TCPDF\TextColor;

class Page
{

    public $numero = 0;

    public $zones = [
        0 => null,
        1 => null,
        2 => null,
        3 => null,
    ];

    public function setActive($index, $color = null)
    {
        $this->zones[$index] = is_null($color) ? Color::cmyk(0, 100, 100, 0) : $color;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $m = 1;
        $w = 4.2;
        $h = 6;

        // add the margin
        $position = $position->add(new Position($m, $m));

        $d = new Dimensions($w / 2, $h / 2);
        $borderStyle = new LineStyle();
        $borderStyle->color = Color::black();
        $borderStyle->width = 0.05;
        $borderStyle->apply($pdf);
        foreach ($this->zones as $i => $zone) {
            $rect             = new Rect();
            $rect->position   = new Position(
                $position->x + (($w / 2) * ($i % 2)),
                $position->y + (($h / 2) * floor($i / 2))
            );
            $rect->dimensions = $d;
            $rect->style      = Rect::STYLE_FILL_THEN_STROKE;
            $rect->fillColor  = new FillColor(isset($this->zones[$i]) ? $this->zones[$i] : Color::white());
            $rect->draw($pdf);
        }

        $cell                  = new Cell();
        $cell->position        = $position->add(new Position(0, $h));
        $cell->width           = $w;
        $cell->height          = 4;
        $cell->align           = Cell::ALIGN_CENTER;
        $cell->text            = $this->numero;
        $cell->border          = 0;
        $cell->font            = new Font('bagc-bold', 10, new TextColor(Color::black()));
        $cell->ignoreMinHeight = true;
        $cell->draw($pdf);
    }
} 