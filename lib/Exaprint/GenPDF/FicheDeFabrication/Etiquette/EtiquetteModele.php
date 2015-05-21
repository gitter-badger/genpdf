<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Etiquette;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\TextColor;

class EtiquetteModele extends Commande
{

    public function grille() {
        // Ne pas afficher la grille
    }

}
