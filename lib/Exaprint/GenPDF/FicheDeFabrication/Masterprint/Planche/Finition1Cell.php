<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:00
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Cellule;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinition;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\TextColor;

class Finition1Cell extends NegoceFinition
{
    public $Type = null;

    public $Recto = false;

    public $Verso = false;

    public $fontTitle = 18;

    public function __construct($planche)
    {
        parent::__constructor($planche);

        $cell             = new Cellule();
        $cell->dimensions = new Dimensions(100, 11);
        $cell->fillColor  = new FillColor(Color::cmyk(100, 0, 0, 0));
        $cell->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));
        $cell->vAlign     = Cell::VALIGN_CENTER;

        $this->_cell = $cell;

        $this->cellules = [
            &$this->_cell,
        ];
    }

    public function setValue($label)
    {
        if (strlen($label) > 40) {
            $this->_cell->valueFont->size = 16;
        }

        $this->_cell->value = $label;
    }


} 