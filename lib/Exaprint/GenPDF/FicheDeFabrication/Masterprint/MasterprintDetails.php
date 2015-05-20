<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\Details;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\TextColor;

class MasterprintDetails extends Details
{

    protected function content()
    {
        $html = MasterprintDAL::cleanOrderComment($this->commande['IDCommande']);
        $fontSize = 14;
        if (count($html) >= 20) {$fontSize = 13;}
        if (count($html) >= 25) {$fontSize = 12;}
        if (count($html) >= 30) {$fontSize = 11;}
        if (count($html) >= 35) {$fontSize = 10;}

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y(5);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc();
        $c->isHtml          = true;
        $c->font = new Font('bagc-light', $fontSize);
        $c->text      = $html;
        $c->textColor = new TextColor(Color::black());
        $c->draw($this->pdf);
    }
}
