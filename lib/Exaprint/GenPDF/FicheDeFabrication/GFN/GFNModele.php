<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\GFN;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Formatter;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Layout;
use Exaprint\GenPDF\FicheDeFabrication\Helper;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class GFNModele extends Commande
{

    function __construct($commande, \TCPDF $pdf, $x, $y, Layout $layout)
    {
        $this->pdf      = $pdf;
        $this->x        = $x;
        $this->y        = $y;
        $this->commande = $commande;

        $this->layout = $layout;

        $this->ids();
        $this->formats();
        $this->quantite();
        $this->visuels();
        $this->surVisuels();
        $this->commentaires();
        $this->border();
    }

    protected function ids()
    {

        $w = $this->layout->cEnteteIdsWidth;

        $idCommande                  = new Cell();
        $idCommande->font            = new Font('bagc-bold', 20);
        $idCommande->fillColor       = new FillColor(Color::greyscale(0));
        $idCommande->position        = new Position($this->_x(), $this->_y());
        $idCommande->textColor       = new TextColor(Color::greyscale(255));
        $idCommande->ignoreMinHeight = true;
        $idCommande->align           = Cell::ALIGN_CENTER;
        $idCommande->vAlign          = Cell::VALIGN_CENTER;
        $idCommande->height          = $this->layout->cEnteteHeight;
        $idCommande->width           = $w;
        $idCommande->text            = $this->commande['modele']->title;
        $idCommande->fill            = true;
        $idCommande->border          = true;
        $idCommande->draw($this->pdf);

    }

    protected function formats()
    {
        $times    = '×';
        $text     = $this->commande['modele']->size->width . $times . $this->commande['modele']->size->height;
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
        $q                  = new Cell();
        $q->width           = $this->layout->cEnteteQuantiteWidth;
        $q->font            = new Font('bagc-bold', 28);
        $q->fillColor       = new FillColor(Color::cmyk(100, 100, 0, 0));
        $q->textColor       = new TextColor(Color::greyscale(255));
        $q->text            = Formatter::quantite($this->commande['Quantite']) . ' ex';
        $q->position        = new Position($this->_x($this->layout->wBloc() - $q->width), $this->_y());
        $q->height          = $this->layout->cEnteteHeight;
        $q->align           = Cell::ALIGN_CENTER;
        $q->ignoreMinHeight = true;
        $q->vAlign          = Cell::VALIGN_BOTTOM;
        $q->fill            = true;
        $q->draw($this->pdf);
    }

    protected function surVisuels()
    {
        $c            = new Cell();
        $c->width     = 48;
        $c->height    = 4;
        $c->text      = Helper::short($this->commande['CodeProduit'], 80);
        $c->font      = new Font('bagc-medium', 9, new TextColor(Color::black()));
        $c->fill      = true;
        $c->fillColor = new FillColor(Color::white());
        $c->position  = new Position($this->_x(0.5), $this->_y($this->layout->cEnteteHeight + $this->layout->hGrille() + 1.5));
        $c->draw($this->pdf);
    }

    protected function commentaires()
    {

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y($this->layout->cEnteteHeight + $this->layout->cVisuelsHeight + 1);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc() - $this->layout->cellule() * $this->layout->cGrilleColCount;
        $c->isHtml          = true;

        $text    = 'Commentaires PAO : ' . $this->commande['CommentaireAtelier'];
        $c->font = new Font('bagc-light', 15);
        if (strlen($text) > 250) {
            $c->font = new Font('bagc-light', 13);
        }
        if (strlen($text) > 500) {
            $c->font = new Font('bagc-light', 11);
        }

        $c->text = '';
        $c->text .= '<span style="font-weight:bold; font-size: 11px;">';
        $c->text .= $this->commande['modele']->title;
        $c->text .= '<br />Surface : ' . ($this->commande['modele']->size->width * $this->commande['modele']->size->height / 1000) . " m2";
        $c->text .= '</span>';
        if (!empty($this->commande['CommentaireAtelier'])) {
            $c->text .= '<br />Commentaires PAO : <span style="background-color:#ededb6">' . $this->commande['CommentaireAtelier'] . '</span>';
        }
        $c->textColor = new TextColor(Color::black());
        $c->textColor = new TextColor(Color::black());
        $c->draw($this->pdf);
    }

}
