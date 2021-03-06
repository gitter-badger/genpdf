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

class EtiquetteModele extends Commande
{

    function __construct($commande, \TCPDF $pdf, $x, $y, Layout $layout)
    {
        $this->pdf      = $pdf;
        $this->x        = $x;
        $this->y        = $y;
        $this->commande = $commande;
        $this->layout   = $layout;

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
        $idCommande->text            = $this->commande['modele']->attributes()->name;

        $idCommande->font = new Font('bagc-bold', 20);
        if (strlen($idCommande->text) > 6) {
            $idCommande->font = new Font('bagc-bold', 16);
        }

        $idCommande->fill            = true;
        $idCommande->border          = true;
        $idCommande->draw($this->pdf);

    }

    protected function quantite()
    {
        $q                  = new Cell();
        $q->width           = $this->layout->cEnteteQuantiteWidth;
        $q->font            = new Font('bagc-bold', 20);
        $q->fillColor       = new FillColor(Color::cmyk(100, 100, 0, 0));
        $q->textColor       = new TextColor(Color::greyscale(255));
        $q->text            = Formatter::quantite((double)$this->commande['modele']->attributes()->quantity) . ' ex';
        $q->position        = new Position($this->_x($this->layout->wBloc() - $q->width), $this->_y());
        $q->height          = $this->layout->cEnteteHeight;
        $q->align           = Cell::ALIGN_CENTER;
        $q->ignoreMinHeight = true;
        $q->vAlign          = Cell::VALIGN_CENTER;
        $q->fill            = true;
        $q->draw($this->pdf);
    }

    protected function surVisuels()
    {
        $c            = new Cell();
        $c->width     = 48;
        $c->height    = 4;
        $c->text      = Helper::short($this->commande['CodeProduit'], 42);
        $c->font      = new Font('bagc-medium', 9, new TextColor(Color::black()));
        $c->fill      = true;
        $c->fillColor = new FillColor(Color::white());
        $c->position  = new Position($this->_x(0.5), $this->_y($this->layout->cEnteteHeight + $this->layout->hGrille() + 1.5));
        $c->draw($this->pdf);

        $c            = new Cell();
        $c->width     = 48;
        $c->height    = 4;
        $c->text      = 'Ref. ' . $this->commande['modele']->attributes()->name;
        $c->font      = new Font('bagc-medium', 9, new TextColor(Color::black()));
        $c->fill      = true;
        $c->fillColor = new FillColor(Color::white());
        $c->position  = new Position($this->_x(49.5), $this->_y($this->layout->cEnteteHeight + $this->layout->hGrille() + 1.5));
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

        $text    = $this->commande['CommentaireAtelier'];
        $c->font = new Font('bagc-light', 15);
        if (strlen($text) > 250) {
            $c->font = new Font('bagc-light', 13);
        }
        if (strlen($text) > 500) {
            $c->font = new Font('bagc-light', 11);
        }

        $c->text = '';
        $c->text .= '<span style="font-weight:bold; font-size: 11px;">';
        $c->text .= 'Nombre de rouleau par modèle : ' . ((double)$this->commande['modele']->attributes()->quantity / $this->commande['modele']['EtiquetteparRouleau']);
        $c->text .= '</span>';

        if (!empty($this->commande['CommentaireAtelier'])) {
            $c->text      = '<span style="background-color:#ededb6">' . $this->commande['CommentaireAtelier'] . '</span>';
        }
        $c->textColor = new TextColor(Color::black());
        $c->draw($this->pdf);
    }

}
