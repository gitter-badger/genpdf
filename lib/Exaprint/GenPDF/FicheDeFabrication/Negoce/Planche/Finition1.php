<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:00
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Cellule;
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Finition;
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Rang;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\TextColor;

class Finition1 extends Rang
{
    public $Type = null;

    public function __construct()
    {
        $cellTitle             = new Cellule();
        $cellTitle->dimensions = new Dimensions(22, 11);
        $cellTitle->fillColor  = new FillColor(Color::greyscale(80));
        $cellTitle->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));

        $cellA1                  = new Cellule();
        $cellA1->dimensions      = new Dimensions(53, 11);
        $cellA1->fillColor       = new FillColor(Color::cmyk(0, 0, 75, 0));
        $cellA1->valueFont       = new Font('bagc-light', 12, new TextColor(Color::black()));
        $cellA1->valueFont->size = 14;
        $cellA1->align           = Cell::ALIGN_LEFT;
        $cellA1->vAlign          = Cell::VALIGN_CENTER;

        $cellA2                       = new Cellule();
        $cellA2->dimensions           = new Dimensions(10, 11);
        $cellA2->fillColor            = new FillColor(Color::white());
        $cellA2->valueFont            = new Font('bagc-light', 12, new TextColor(Color::black()));
        $cellA2->vAlign               = Cell::VALIGN_CENTER;
        $cellA2->label                = 'Coul.';

        $cellA3                       = new Cellule();
        $cellA3->dimensions           = new Dimensions(15, 11);
        $cellA3->fillColor            = new FillColor(Color::red());
        $cellA3->valueFont            = new Font('bagc-light', 12, new TextColor(Color::white()));
        $cellA3->vAlign               = Cell::VALIGN_CENTER;
        $cellA3->label                = 'Lamination.';
        $cellA3->labelFont->textColor = new TextColor(Color::white());

        $this->_cellTitle = $cellTitle;
        $this->_cellA1    = $cellA1;
        $this->_cellA2    = $cellA2;
        $this->_cellA3    = $cellA3;

        $this->cellules = [
            &$this->_cellTitle,
            &$this->_cellA1,
            &$this->_cellA2,
            &$this->_cellA3,
        ];
    }

    public function setTitle($label)
    {
        $this->_cellTitle->value = $label;
    }

    public function setA1($label)
    {
        $this->_cellA1->value = $label;
    }

    public function setA2($label)
    {
        $this->_cellA2->value = $label;
    }

    public function getA2()
    {
        return $this->_cellA2->value;
    }

    public function setA3($label)
    {
        $this->_cellA3->value = $label;
    }


} 