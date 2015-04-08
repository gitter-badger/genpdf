<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 15:04
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Stylesheet;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Compteur
{

    public $label = '';

    public $count = 0;

    public $labelFillColor;

    public $countFillColor;

    public function draw(\TCPDF $pdf, Position $position)
    {
        if (!isset($this->labelFillColor)) $this->labelFillColor = new FillColor(Color::greyscale(100));
        if (!isset($this->countFillColor)) $this->countFillColor = new FillColor(Color::white());

        Stylesheet::lineStyle()->apply($pdf);

        $labelCell = new Cell();
        $labelCell->border = 1;
        $labelCell->width = 10;
        $labelCell->height = 4;
        $labelCell->font = new Font('bagc-medium', '11', new TextColor(Color::white()));
        $labelCell->text = $this->label;
        $labelCell->fill = true;
        $labelCell->vAlign = Cell::VALIGN_CENTER;
        $labelCell->align = Cell::ALIGN_CENTER;
        $labelCell->fillColor = $this->labelFillColor;
        $labelCell->position = $position;
        $labelCell->draw($pdf);

        $countCell = new Cell();
        $countCell->border = 1;
        $countCell->fill = true;
        $countCell->fillColor = $this->countFillColor;
        $countCell->align = Cell::VALIGN_CENTER;
        $countCell->text = ($this->count > 0) ? $this->count : '';
        $countCell->width = 10;
        $countCell->height = 10;
        $countCell->position = $position->add(new Position(0, 4));
        $countCell->font = new Font('bagc-bold', '26', new TextColor(Color::black()));
        $countCell->draw($pdf);

        Stylesheet::lineStyle()->revert($pdf);

    }
} 