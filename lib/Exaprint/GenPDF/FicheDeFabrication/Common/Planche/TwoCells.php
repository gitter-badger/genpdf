<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Planche;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class TwoCells
{

    /** @var string */
    public $value1 = '';
    /** @var string */
    public $value2 = '';
    /** @var int */
    public $width;
    /** @var int */
    public $height;

    public function __construct()
    {
        $this->applyDefaults();
    }

    protected function applyDefaults()
    {
        // todo ?
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $p = $position;

        $cell                  = new Cell();
        $cell->position        = $p;
        $cell->width           = 10;
        $cell->height          = 5;
        $cell->text            = $this->value1;
        $cell->align           = Cell::ALIGN_CENTER;
        $cell->vAlign          = Cell::VALIGN_BOTTOM;
        $cell->ignoreMinHeight = true;
        $cell->font            = new Font('bagc-bold', 14, new TextColor(Color::black()));
        $cell->border          = false;
        $cell->fillColor       = new FillColor(Color::white());
        $cell->draw($pdf);

        $np = $p->add(new Position(0, $this->height / 2));

        $cell                  = new Cell();
        $cell->position        = $np;
        $cell->width           = $this->width;
        $cell->height          = $this->height / 2;
        $cell->text            = $this->value2;
        $cell->align           = Cell::ALIGN_CENTER;
        $cell->vAlign          = CELL::VALIGN_TOP;
        $cell->ignoreMinHeight = true;
        $cell->font            = new Font('bagc-medium', 10, new TextColor(Color::black()));
        $cell->border          = false;
        $cell->fillColor       = new FillColor(Color::white());
        $cell->draw($pdf);

        $cell           = new Cell();
        $cell->position = $p;
        $cell->width    = $this->width;
        $cell->height   = $this->height;
        $cell->border   = true;
        $cell->draw($pdf);
    }
} 