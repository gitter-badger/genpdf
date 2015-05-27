<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Etiquette;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Formatter;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class EtiquetteCommande extends Commande
{

    protected function formats()
    {
        $times = '×';
        $text  = $this->commande['LargeurOuvert'] . $times . $this->commande['LongueurOuvert'];
        $fontSize = 28;
        if ($text == '×') {
            $text = '';
        }
        if (strlen($text) > 10) {
            $fontSize = 22;
        }

        $cOuvert                  = new Cell();
        $cOuvert->textColor       = new TextColor(Color::greyscale(0));
        $cOuvert->position        = new Position($this->_x($this->layout->cEnteteIdsWidth), $this->_y());
        $cOuvert->text            = $text;
        $cOuvert->font            = new Font('bagc-bold', $fontSize);
        $cOuvert->ignoreMinHeight = true;
        $cOuvert->border          = Cell::BORDER_NO_BORDER;
        $cOuvert->height          = $this->layout->cEnteteHeight;
        $cOuvert->width           = $this->layout->wBloc() - $this->layout->cEnteteQuantiteWidth - $this->layout->cEnteteIdsWidth;
        $cOuvert->align           = Cell::ALIGN_CENTER;
        $cOuvert->vAlign          = Cell::VALIGN_CENTER;
        $cOuvert->border          = true;

        if ($this->commande['LongueurFerme']) {

            $cOuvert->font   = new Font('bagc-bold', 18);
            $cOuvert->height = 6;

            $cFerme                  = new Cell();
            $cFerme->textColor       = new TextColor(Color::greyscale(0));
            $cFerme->position        = new Position($this->_x($this->layout->cEnteteIdsWidth), $this->_y(6));
            $cFerme->text            = $this->commande['LargeurFerme'] . $times . $this->commande['LongueurFerme'];
            $cFerme->font            = new Font('bagc-mediumital', 12);
            $cFerme->ignoreMinHeight = true;
            $cFerme->border          = Cell::BORDER_NO_BORDER;
            $cFerme->height          = 4;
            $cFerme->width           = $this->layout->wBloc() - $this->layout->cEnteteQuantiteWidth - $this->layout->cEnteteIdsWidth;
            $cFerme->align           = Cell::ALIGN_CENTER;
            $cFerme->vAlign          = Cell::VALIGN_BOTTOM;

            $cFerme->draw($this->pdf);
        }
        $cOuvert->draw($this->pdf);

    }

    protected function quantite()
    {
        $text = "";
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
        $q->width           = $this->layout->cEnteteQuantiteWidth;
        $q->font            = new Font('bagc-bold', 18);
        $q->fillColor       = new FillColor(Color::cmyk(100, 0, 0, 0));
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

        $text = $this->commande['CommentairePAO'] . $this->commande['CommentaireAtelier'];
        $c->font = new Font('bagc-light', 15);
        if (strlen($text) > 250) {
            $c->font = new Font('bagc-light', 13);
        }
        if (strlen($text) > 500) {
            $c->font = new Font('bagc-light', 11);
        }

        $c->text      = $this->commande['CommentairePAO'] . "<br />" . '<span style="background-color:#ededb6">' . $this->commande['CommentaireAtelier'] . '</span>';
        $c->text = str_replace("\n", '<br />', $c->text);
        $c->textColor = new TextColor(Color::black());
        $c->draw($this->pdf);
    }

}
