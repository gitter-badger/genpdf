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
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\LineStyle;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Text;
use Exaprint\TCPDF\TextColor;

class Cellule
{

    /** @var string  */
    public $label = '';
    /** @var string  */
    public $value = '';
    /** @var Font */
    public $valueFont;
    /** @var Font */
    public $labelFont;
    /** @var FillColor */
    public $fillColor;
    /** @var string  */
    public $align = Cell::VALIGN_CENTER;
    /** @var string  */
    public $vAlign = Cell::VALIGN_BOTTOM;
    /** @var  Dimensions */
    public $dimensions;
    /** @var  bool */
    public $noDraw = false;

    public function __construct()
    {
        $this->applyDefaults();
    }

    protected function applyDefaults()
    {
        $this->valueFont = new Font('bagc-bold', 36, new TextColor(Color::black()));
        $this->labelFont = new Font('bagc-light', 10, new TextColor(Color::greyscale(40)));
        $this->fillColor = new FillColor(Color::white());
        $this->dimensions = new Dimensions(0, 0);
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        if ($this->noDraw) return;

        $value = $this->value;
        $p     = $position;
        $d     = $this->dimensions;
        if (is_numeric($value) && $value == 0)
            $value = '';


        if (!$value) {
            $lineStyle        = new LineStyle();
            $lineStyle->color = Color::greyscale(200);
            $lineStyle->width = 0.2;
            $lineStyle->apply($pdf);
            $pdf->Line($p->x, $p->y, $d->width + $p->x, $d->height + $p->y);
            $pdf->Line($d->width + $p->x, $p->y, $p->x, $d->height + $p->y);
            $lineStyle->revert($pdf);
        }

        $cell                  = new Cell();
        $cell->position        = $p;
        $cell->width           = $d->width;
        $cell->height          = $d->height;
        $cell->text            = $value;
        $cell->align           = $this->align;
        $cell->vAlign          = $this->vAlign;
        $cell->ignoreMinHeight = true;
        $cell->font            = $this->valueFont;
        $cell->fill            = ($value);
        $cell->border          = true;
        $cell->fillColor       = $this->fillColor;
        $cell->draw($pdf);


        if ($this->label) {
            $labelText = new Text($p->x, $p->y, $this->label, $this->labelFont);
            $labelText->draw($pdf);
        }
    }
} 