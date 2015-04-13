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
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Finition3 extends NegoceFinition
{
    public $Type = null;

    public function __construct()
    {
        $cellOption1             = new Cellule();
        $cellOption1->dimensions = new Dimensions(11, 11);
        $cellOption1->fillColor  = new FillColor(Color::greyscale(80));
        $cellOption1->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));
        $cellOption1->value      = '+';
        $cellOption1->vAlign     = Cell::VALIGN_CENTER;

        $cellA1                  = new Cellule();
        $cellA1->dimensions      = new Dimensions(39, 11);
        $cellA1->fillColor       = new FillColor(Color::cmyk(0, 0, 75, 0));
        $cellA1->valueFont       = new Font('bagc-light', 12, new TextColor(Color::black()));
        $cellA1->valueFont->size = 14;
        $cellA1->align           = Cell::ALIGN_LEFT;
        $cellA1->vAlign          = Cell::VALIGN_CENTER;

        $cellOption2             = new Cellule();
        $cellOption2->dimensions = new Dimensions(11, 11);
        $cellOption2->fillColor  = new FillColor(Color::greyscale(80));
        $cellOption2->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));
        $cellOption2->value      = '+';
        $cellOption2->vAlign     = Cell::VALIGN_CENTER;
        $cellOption2->noDraw     = true;

        $cellA2                  = new Cellule();
        $cellA2->dimensions      = new Dimensions(39, 11);
        $cellA2->fillColor       = new FillColor(Color::cmyk(0, 0, 75, 0));
        $cellA2->valueFont       = new Font('bagc-light', 12, new TextColor(Color::black()));
        $cellA2->valueFont->size = 14;
        $cellA2->align           = Cell::ALIGN_LEFT;
        $cellA2->vAlign          = Cell::VALIGN_CENTER;
        $cellA2->noDraw          = true;

        $this->_cellOption1 = $cellOption1;
        $this->_cellA1      = $cellA1;
        $this->_cellOption2 = $cellOption2;
        $this->_cellA2      = $cellA2;

        $this->cellules = [
            &$this->_cellOption1,
            &$this->_cellA1,
            &$this->_cellOption2,
            &$this->_cellA2,
        ];
    }

    public function setOption1($label)
    {
        $this->_cellOption1->value = $label;
    }

    public function setA1($label)
    {
        $this->_cellA1->value = $label;
    }

    public function setOption2($label)
    {
        $this->_cellOption2->value  = $label;
        $this->_cellOption2->noDraw = false;
    }

    public function setA2($label)
    {
        $this->_cellA2->value  = $label;
        $this->_cellA2->noDraw = false;
    }

    public function build()
    {
        $ret = [];

        foreach ($this->entries as $entry) {
            $ret[] = $entry->LibelleValeurPredefinie ? $entry->LibelleValeurPredefinie : $entry->LibelleValeur;
        }

        foreach ($ret as $n => $libelle) {
            if ($n % 2 == 0) {
                $this->setOption1('+');
                $this->setA1($libelle);
            } else {
                $this->setOption2('+');
                $this->setA2($libelle);
            }
        }
    }


} 