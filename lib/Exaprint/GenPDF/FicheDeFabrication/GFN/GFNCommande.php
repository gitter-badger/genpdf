<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\GFN;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class GFNCommande extends Commande
{

    protected function formats()
    {
        $text      = "";
        $nbModeles = $this->commande['nbModeles'];

        if ($nbModeles == 0) {
            $text = "0 modèles";
        }
        if ($nbModeles == 1) {
            $text = "1 modèle";
        }
        if ($nbModeles > 1) {
            $text = "$nbModeles modèles";
        }

        $q                  = new Cell();
        $q->textColor       = new TextColor(Color::greyscale(0));
        $q->position        = new Position($this->_x($this->layout->cEnteteIdsWidth), $this->_y());
        $q->text            = $text;
        $q->font            = new Font('bagc-bold', 22);
        $q->ignoreMinHeight = true;
        $q->border          = Cell::BORDER_NO_BORDER;
        $q->height          = $this->layout->cEnteteHeight;
        $q->width           = $this->layout->wBloc() - $this->layout->cEnteteQuantiteWidth - $this->layout->cEnteteIdsWidth;
        $q->align           = Cell::ALIGN_CENTER;
        $q->vAlign          = Cell::VALIGN_CENTER;
        $q->border          = true;
        $q->draw($this->pdf);

    }

    protected function quantite()
    {
        $text = $this->commande['surfaceTotale'] . " m2";

        $q                  = new Cell();
        $q->width           = $this->layout->cEnteteQuantiteWidth;
        $q->font            = new Font('bagc-bold', 18);
        $q->fillColor       = new FillColor(Color::cmyk(100, 100, 0, 0));
        $q->textColor       = new TextColor(Color::greyscale(255));
        $q->text            = $text;
        $q->position        = new Position($this->_x($this->layout->wBloc() - $q->width), $this->_y());
        $q->height          = $this->layout->cEnteteHeight;
        $q->align           = Cell::ALIGN_CENTER;
        $q->ignoreMinHeight = true;
        $q->vAlign          = Cell::VALIGN_CENTER;
        $q->fill            = true;
        $q->border          = true;
        $q->draw($this->pdf);
    }

    protected function visuels()
    {
        // Ne pas afficher les visuels
    }

    protected function surVisuels()
    {
        // Ne pas afficher les libellés sur visuels
    }

    protected function commentaires()
    {

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y($this->layout->cEnteteHeight + 2);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc() - $this->layout->cellule() * $this->layout->cGrilleColCount;
        $c->isHtml          = true;

        $text = $this->_clean($this->commande['CommentairePAO']);
        $text .= $this->commande['CommentaireAtelier'];
        $c->font = new Font('bagc-light', 15);
        if (strlen($text) > 250) {
            $c->font = new Font('bagc-light', 13);
        }
        if (strlen($text) > 500) {
            $c->font = new Font('bagc-light', 11);
        }

        $c->text = $text;
        //$c->text      = $this->commande['CommentairePAO'] . "<br />" . '<span style="background-color:#ededb6">' . $this->commande['CommentaireAtelier'] . '</span>';
        $c->text      = str_replace("\n", '<br />', $c->text);
        $c->textColor = new TextColor(Color::black());
        $c->draw($this->pdf);
    }

    private function _clean($text)
    {

        $res   = [];
        $lines = explode("\n", $text);
        $calc = false;

        foreach ($lines as $n => $line) {
            $put = true;
            if (strpos($line, 'CALCULATEUR') !== false) {
                $calc = true;
                $put = false;
            }
            if (!$calc) {
                $extraLines[] = $line;
                $put = false;
            }
            if (strpos($line, 'Intitulé') !== false) {
                $put = false;
            }
            if (strpos($line, 'Modèle n°5') !== false) {
                $res[] = '';
                $res[] = 'Pour de plus amples détails, référez-vous à votre devis.';
                break;
            }
            if ($put) {
                $res[] = $line;
            }
        }

        $res = array_merge($res, $extraLines);

        return implode('<br />', $res);
    }

}
