<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:00
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Cellule;
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\CelluleMultiligne;
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Finition;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class FinitionMax extends NegoceFinition
{
    public $Type = null;

    public $value;

    public function __construct($planche)
    {
        parent::__constructor($planche);

        $cell             = new CelluleMultiligne();
        $cell->dimensions = new Dimensions(100, 8);
        $cell->fillColor  = new FillColor(Color::red());
        $cell->textFont   = new Font('bagc-light', 18, new TextColor(Color::white()));
        $cell->isHtml     = true;
        $cell->vAlign     = MultiCell::VALIGN_MIDDLE;

        $this->_cell = $cell;

        $this->cellules = [
            &$this->_cell,
        ];
    }

    public function setValue($value) {
        $image = '<img src="../assets/Arrow.png" width="16" height="16" />';
        $this->_cell->text = "$image $value";
    }


} 