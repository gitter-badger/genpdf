<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\TextColor;

class MasterprintCommande extends Commande
{

    protected function commentaires()
    {

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y($this->layout->cEnteteHeight + $this->layout->cVisuelsHeight + 1);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc() - $this->layout->cellule() * $this->layout->cGrilleColCount;
        $c->isHtml          = true;

        $CommentairePAO = MasterprintDAL::cleanOrderComment($this->commande['IDCommande']);

        $text = $CommentairePAO . $this->commande['CommentaireAtelier'];
        $c->font = new Font('bagc-light', 15);
        if (strlen($text) > 250) {
            $c->font = new Font('bagc-light', 13);
        }
        if (strlen($text) > 500) {
            $c->font = new Font('bagc-light', 11);
        }

        $c->text      = $CommentairePAO . "<br />" . '<span style="background-color:#ededb6">' . $this->commande['CommentaireAtelier'] . '</span>';
        $c->textColor = new TextColor(Color::red());
        $c->draw($this->pdf);
    }

}
