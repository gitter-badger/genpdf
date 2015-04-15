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

class Finition2 extends NegoceFinition
{
    public $Type = null;

    public $Recto = false;

    public $Verso = false;

    public function __construct()
    {
        $cellTitle             = new Cellule();
        $cellTitle->dimensions = new Dimensions(22, 11);
        $cellTitle->fillColor  = new FillColor(Color::greyscale(80));
        $cellTitle->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));
        $cellTitle->vAlign     = Cell::VALIGN_CENTER;

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
        if (strlen($label) > 4) {
            $this->_cellTitle->valueFont->size = 26;
        }
        $this->_cellTitle->value = $label;
    }

    public function setA1($label)
    {
        $max = 35;
        if (strlen($label) > $max) {
            $label = str_split($label, $max)[0] . '...';
        }
        $this->_cellA1->value = $label;
    }

    public function setA2()
    {
        if ($this->Recto and $this->Verso) {
            $this->_cellA2->src = '../assets/RectoVerso.png';
        } else if ($this->Recto) {
            $this->_cellA2->src = '../assets/Recto.png';
        } else if ($this->Verso) {
            $this->_cellA2->src = '../assets/Verso.png';
        }
    }

    public function build()
    {
        foreach ($this->entries as $entry) {
            switch ($entry->Encadre) {
                case 0:
                    $this->setTitle($entry->TitreAlternatif ? $entry->TitreAlternatif : $entry->Titre);
                    break;
                case 1:
                    $this->setTitle($entry->TitreAlternatif ? $entry->TitreAlternatif : $entry->Titre);
                    $libelle = $entry->LibelleValeurPredefinie ? $entry->LibelleValeurPredefinie : $entry->LibelleValeur;

                    $libelle = preg_replace('/R°/', '', $libelle);
                    $libelle = preg_replace('/V°/', '', $libelle);

                    $this->setA1($libelle);
                    if (!$this->Recto) {
                        $this->Recto = $entry->EstRecto;
                    }
                    if (!$this->Verso) {
                        $this->Verso = $entry->EstVerso;
                    }
                    break;
            }
        }

        // finalisation A2
        $this->setA2();

    }


} 