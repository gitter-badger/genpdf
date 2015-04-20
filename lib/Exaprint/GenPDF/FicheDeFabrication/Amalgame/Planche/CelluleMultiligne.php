<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\LineStyle;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Text;
use Exaprint\TCPDF\TextColor;

class CelluleMultiligne
{

    /** @var  String */
    public $label;
    /** @var  String */
    public $text;
    /** @var  Dimensions */
    public $dimensions;
    /** @var  FillColor */
    public $fillColor;
    /** @var  Font */
    public $labelFont;
    /** @var  Font */
    public $textFont;
    /** @var string  */
    public $vAlign = MultiCell::VALIGN_BOTTOM;
    /** @var bool  */
    public $isHtml = false;

    public function __construct()
    {
        $this->applyDefaults();
    }

    protected function applyDefaults()
    {
        $this->dimensions = new Dimensions(0, 0);
        $this->labelFont  = new Font('bagc-light', 10, new TextColor(Color::greyscale(40)));
        $this->textFont   = new Font('bagc-reg', 13, new TextColor(Color::black()));
        $this->fillColor  = new FillColor(Color::white());
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $value = $this->text;
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

        $c            = new MultiCell();
        $c->text      = $value;
        $c->font      = $this->textFont;
        $c->fill      = true;
        $c->fillColor = $this->fillColor;
        $c->width     = $d->width;
        $c->height    = $d->height;
        $c->maxHeight = $d->height;
        $c->align     = MultiCell::ALIGN_LEFT;
        $c->vAlign    = $this->vAlign;
        $c->x         = $position->x;
        $c->y         = $position->y;
        $c->border    = 1;
        $c->isHtml    = $this->isHtml;
        $c->draw($pdf);

        if ($this->label) {
            $labelText = new Text($p->x, $p->y, $this->label, $this->labelFont);
            $labelText->draw($pdf);
        }
    }
} 