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
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\ImageCellule;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\TextColor;

class Finition2 extends Finition
{
    public $Type = null;

    public function __construct()
    {
        $cellTitle             = new Cellule();
        $cellTitle->dimensions = new Dimensions(22, 11);
        $cellTitle->fillColor  = new FillColor(Color::greyscale(80));
        $cellTitle->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));

        $cellA1                  = new Cellule();
        $cellA1->dimensions      = new Dimensions(68, 11);
        $cellA1->fillColor       = new FillColor(Color::cmyk(0, 0, 75, 0));
        $cellA1->valueFont       = new Font('bagc-light', 12, new TextColor(Color::black()));
        $cellA1->valueFont->size = 14;
        $cellA1->align           = Cell::ALIGN_LEFT;
        $cellA1->vAlign          = Cell::VALIGN_CENTER;

        $cellA2             = new ImageCellule();
        $cellA2->dimensions = new Dimensions(10, 11);

        $this->_cellTitle = $cellTitle;
        $this->_cellA1    = $cellA1;
        $this->_cellA2    = $cellA2;

        $this->cellules = [
            &$this->_cellTitle,
            &$this->_cellA1,
            &$this->_cellA2,
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

    public function setA2($recto, $verso)
    {
        if ($verso) {
            $this->_cellA2->src = '../assets/RectoVerso.png';
        } else if ($recto) {
            $this->_cellA2->src = '../assets/Recto.png';
        }
    }


} 