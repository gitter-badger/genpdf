<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:00
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Configurateur\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\CelluleMultiligne;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinition;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\TextColor;

class FinitionMasterprint extends NegoceFinition
{
    public $Type = null;

    public $value;

    public function __construct($planche)
    {
        parent::__constructor($planche);

        $cell             = new CelluleMultiligne();
        $cell->dimensions = new Dimensions(100, 11);
        $cell->fillColor  = new FillColor(Color::cmyk(100, 0, 0, 0));
        $cell->textFont   = new Font('bagc-light', 18, new TextColor(Color::white()));
        $cell->vAlign     = MultiCell::VALIGN_MIDDLE;

        $this->_cell = $cell;

        $this->cellules = [
            &$this->_cell,
        ];
    }

    public function setValue($value) {
        $this->_cell->text = $value;
    }


} 