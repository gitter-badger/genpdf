<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule\CelluleHelper;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Transporteurs;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Image;
use Exaprint\TCPDF\ImageInContainer;
use Exaprint\TCPDF\LineStyle;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;
use Exaprint\GenPDF\FicheDeFabrication\Helper;

class Commande
{

    const TRANSPORTEUR_EXPRESS = 'EXPRESS';

    /**
     * @var \TCPDF
     */
    protected $pdf;

    public $x;

    public $y;

    protected $commande;

    protected $layout;

    function __construct($commande, \TCPDF $pdf, $x, $y, Layout $layout)
    {
        $this->pdf      = $pdf;
        $this->x        = $x;
        $this->y        = $y;
        $this->commande = $commande;
        $this->layout   = $layout;

        $this->grille();
        $this->visuels();
        $this->surVisuels();
        $this->ids();
        $this->commentaires();
        $this->formats();
        $this->quantite();
        $this->livraison();
        $this->transporteur();
        if ($this->commande['EstColise']) {
            $this->codeBarre();
        } else {
            $this->colisage($this->commande['NomAtelier']);
        }
        $this->border();
    }

    protected function _x($x = 0)
    {
        return $this->x + $x;
    }

    protected function _y($y = 0)
    {
        return $this->y + $y;
    }

    protected function border()
    {
        $this->pdf->Rect(
            $this->_x(),
            $this->_y(),
            $this->layout->wBloc(),
            $this->layout->hBloc()
        );
    }

    protected function ids()
    {

        $w                = $this->layout->cEnteteIdsWidth;
        $idCommandeHeight = $this->layout->cIdCommandeHeight;

        $idCommande                  = new Cell();
        $idCommande->font            = new Font('bagc-bold', 20);
        $idCommande->fillColor       = new FillColor(Color::greyscale(0));
        $idCommande->position        = new Position($this->_x(), $this->_y());
        $idCommande->textColor       = new TextColor(Color::greyscale(255));
        $idCommande->ignoreMinHeight = true;
        $idCommande->align           = Cell::ALIGN_CENTER;
        $idCommande->vAlign          = Cell::VALIGN_BOTTOM;
        $idCommande->height          = $idCommandeHeight;
        $idCommande->width           = $w;
        $idCommande->text            = Formatter::idCommande($this->commande['IDCommande']);
        $idCommande->fill            = true;
        $idCommande->draw($this->pdf);

        $idPlanche                  = new Cell();
        $idPlanche->font            = new Font('bagc-light', 10);
        $idPlanche->fillColor       = new FillColor(Color::greyscale(0));
        $idPlanche->position        = new Position($this->_x(), $this->_y($idCommandeHeight));
        $idPlanche->textColor       = new TextColor(Color::greyscale(255));
        $idPlanche->ignoreMinHeight = true;
        $idPlanche->align           = Cell::ALIGN_CENTER;
        $idPlanche->vAlign          = Cell::VALIGN_TOP;
        $idPlanche->height          = $this->layout->cEnteteHeight - $idCommandeHeight;
        $idPlanche->width           = $w;
        $idPlanche->text            = Formatter::idPlanche($this->commande['IDPlanche']);
        $idPlanche->fill            = true;
        $idPlanche->draw($this->pdf);

    }


    protected function quantite()
    {
        $q                  = new Cell();
        $q->width           = $this->layout->cEnteteQuantiteWidth;
        $q->font            = new Font('bagc-bold', 28);
        $q->fillColor       = new FillColor(Color::cmyk(100, 100, 0, 0));
        $q->textColor       = new TextColor(Color::greyscale(255));
        $q->text            = Formatter::quantite($this->commande['Quantite']);
        $q->position        = new Position($this->_x($this->layout->wBloc() - $q->width), $this->_y());
        $q->height          = $this->layout->cEnteteHeight;
        $q->align           = Cell::ALIGN_CENTER;
        $q->ignoreMinHeight = true;
        $q->vAlign          = Cell::VALIGN_BOTTOM;
        $q->fill            = true;
        $q->draw($this->pdf);
    }

    protected function formats()
    {
        $times    = '×';
        $text     = $this->commande['LargeurOuvert'] . $times . $this->commande['LongueurOuvert'];
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

    protected function placeImage($imageData, $containerX, $containerY, $containerWidth, $containerHeight)
    {
        $this->pdf->setAlpha(1);
        $imageWidth     = $imageData['width'];
        $imageHeight    = $imageData['height'];
        $imageRatio     = $imageWidth / $imageHeight;
        $containerRatio = $containerWidth / $containerHeight;

        $rotate = ($imageRatio > 1 && $containerRatio < 1) || ($imageRatio < 1 && $containerRatio > 1);

        $image       = new Image();
        $image->file = $imageData['href'];

        if ($rotate) {

            $this->pdf->StartTransform();
            $this->pdf->Rotate(90,
                $containerX + $containerWidth / 2,
                $containerY + $containerHeight / 2
            );

            $image->width  = $containerHeight;
            $image->height = $containerHeight / $imageRatio;

            if ($image->height > $containerHeight) {
                $image->height = $containerWidth;
                $image->width  = $containerWidth * $imageRatio;
            }

        } else {
            $image->width  = $containerWidth;
            $image->height = $containerWidth / $imageRatio;

            if ($image->height > $containerHeight) {
                $image->height = $containerHeight;
                $image->width  = $containerHeight * $imageRatio;
            }
        }


        $image->x = $containerX - ($image->width - $containerWidth) / 2;
        $image->y = $containerY + ($containerHeight - $image->height) / 2;

        $image->draw($this->pdf);

        if ($rotate) $this->pdf->StopTransform();

        $this->pdf->Rect(
            $containerX,
            $containerY,
            $containerWidth,
            $containerHeight
        );

    }

    protected function visuels()
    {

        $visuels = $this->commande['Fichiers']['Visuels'];

        if (count($visuels) == 0) return;

        if (count($visuels) == 1 || is_null($this->commande['NbCouleursVerso'])) {
            $recto = $visuels[0];
            if (!$recto['width'] || !$recto['height']) return;
            $image             = new ImageInContainer(
                $recto['href'],
                new Dimensions($recto['width'], $recto['height']),
                new Dimensions($this->layout->wBloc(), $this->layout->cVisuelsHeight),
                new Position($this->_x(), $this->_y($this->layout->cEnteteHeight))
            );
            $image->autoRotate = true;
            $image->draw($this->pdf);

        } else {
            $recto = $visuels[0];
            if (!$recto['width'] || !$recto['height']) return;
            $image             = new ImageInContainer(
                $recto['href'],
                new Dimensions($recto['width'], $recto['height']),
                new Dimensions($this->layout->wBloc() / 2, $this->layout->cVisuelsHeight),
                new Position($this->_x(), $this->_y($this->layout->cEnteteHeight))
            );
            $image->autoRotate = true;
            $image->draw($this->pdf);

            $recto = $visuels[1];
            if (!$recto['width'] || !$recto['height']) return;
            $image             = new ImageInContainer(
                $recto['href'],
                new Dimensions($recto['width'], $recto['height']),
                new Dimensions($this->layout->wBloc() / 2, $this->layout->cVisuelsHeight),
                new Position($this->_x($this->layout->wBloc() / 2), $this->_y($this->layout->cEnteteHeight))
            );
            $image->autoRotate = true;
            $image->draw($this->pdf);
        }

        $this->pdf->Rect(
            $this->_x(),
            $this->_y($this->layout->cEnteteHeight),
            $this->layout->wBloc(),
            $this->layout->cVisuelsHeight
        );

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
        $c->text      = Helper::short('Ref : ' . $this->commande['ReferenceClient'], 46);
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

        $lines2 = '';

        // commentaire pao
        $lines1 = explode(chr(13), $this->commande['CommentairePAO']);

        // commentaire amalgame
        if (isset($this->commande['AvecCoupeToutesLesPoses']) && $this->commande['AvecCoupeToutesLesPoses']) {
            if (!empty($lines2)) {
                $lines2 .= chr(13);
            }
            $lines2 .= t('ffa.text.coupetoutesposes');
        }

        if (isset($this->commande['AvecCoupeAuFormat']) && $this->commande['AvecCoupeAuFormat']) {
            if (!empty($lines2)) {
                $lines2 .= chr(13);
            }
            $lines2 .= t('ffa.text.coupeauformat') . $this->commande['LargeurOuvert'] . ' x ' . $this->commande['LargeurFerme'];
        }

        $lines2 = explode(chr(13), $lines2);
        $lines2 = array_map(function($value) {
            return '<span style="color: #000">'.$value.'</span>';
        }, $lines2);

        // commentaire atelier
        $comments = $this->commande['CommentaireAtelier'];
        $lines3 = wordwrap($comments, 60, "%%%");
        $lines3 = explode("%%%", $lines3);
        $lines3 = array_map(function($value) {
            return '<span style="background-color: #ededb6">'.$value.'</span>';
        }, $lines3);

        // fusion des lignes
        $lines = array_merge($lines1, $lines2, $lines3);

        // nombre maximal de lignes
        $max = 10;
        if (count($lines) > $max) {
            $lines   = array_slice($lines, 0, $max);
            $lines[] = '<span style="color: #000">...</span>';
        }

        // calcul de la taille de la font en fonction des lignes
        $c->font = new Font('bagc-light', 11);

        // conversion en string
        $pao = implode('<br />', $lines);

        $c->text      = $pao;
        $c->textColor = new TextColor(Color::red());
        $c->draw($this->pdf);
    }

    protected function colisage($NomAtelier)
    {
        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y($this->layout->hBloc() - 5);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc() - $this->layout->cellule() * $this->layout->cGrilleColCount;
        $c->text            = 'Colisage par : ' . $NomAtelier;
        $c->textColor       = new TextColor(Color::greyscale(0));
        $c->font            = new Font('bagc-light', 11);
        $c->draw($this->pdf);
    }

    protected function codeBarre()
    {
        $this->pdf->write1DBarcode(
            $this->commande['IDCommande'],
            'C39',
            $this->_x($this->layout->cCodeBarreMargin),
            $this->_y($this->layout->hBloc() - $this->layout->cCodeBarreHeight - $this->layout->cCodeBarreMargin),
            $this->layout->cCodeBarreWidth,
            $this->layout->cCodeBarreHeight,
            0.4,
            '',
            'N'
        );
    }

    protected function transporteur()
    {
        //
    }

    protected function livraison()
    {

        $lineStyle        = new LineStyle();
        $lineStyle->color = Color::greyscale(200);
        $lineStyle->apply($this->pdf);
        $lineStyle->width = 0.2;

        $h = $this->layout->cellule();

        $x = $this->layout->wBloc() - $this->layout->cellule() * $this->layout->cGrilleColCount;

        if ($this->commande['EstColise']) {
            $this->livraisonCodePays($x, $h);
            $this->livraisonCodePostal($x, $h);
        }
        $this->livraisonDateExpe($x, $h);
        $this->livraisonTransporteur($x, $h);
        $lineStyle->revert($this->pdf);
    }

    protected function livraisonCodePostal($x, $h)
    {
        $c            = new Cell();
        $c->text      = $this->commande['CodePostal'];
        $c->textColor = new TextColor(Color::greyscale(0));
        $c->font      = new Font('bagc-light', 18);
        $c->border    = true;
        $c->height    = $h;
        $c->align     = Cell::ALIGN_RIGHT;
        $c->width     = $this->layout->cLivraisonCodePostalWidth;
        $c->position  = new Position(
            $this->_x($x - $this->layout->cLivraisonCodePaysWidth - $this->layout->cLivraisonCodePostalWidth),
            $this->_y($this->layout->hBloc() - $h)
        );
        $c->draw($this->pdf);
    }

    protected function livraisonTransporteur($x, $h)
    {
        $c            = new Cell();
        $c->text      = Transporteurs::getTransporteurLabel($this->commande['CodeTransporteur']);
        $c->font      = new Font('bagc-bold', $this->layout->cExpeditionDateFontSize);
        $c->border    = true;
        $c->fill      = false;
        $c->align     = Cell::ALIGN_CENTER;
        $c->vAlign    = Cell::VALIGN_CENTER;
        $c->textColor = new TextColor(Color::black());
        $c->position  = new Position(
            $this->_x($x - $this->layout->cExpeditionDateWidth - $this->layout->cTransporteurWidth),
            $this->_y($this->layout->hBloc() - $h * 2)
        );
        $c->width     = $this->layout->cTransporteurWidth;
        $c->height    = $h;

        if ($this->commande['EstImperatif'] || substr($this->commande['CodeProduit'], -4) == 'RUSH') {
            $c->textColor->color = Color::cmyk(0, 0, 100, 0);
            $c->fill             = true;
            $c->fillColor        = new FillColor(Color::cmyk(0, 100, 100, 0));
        }
        $c->draw($this->pdf);
    }

    protected function livraisonDateExpe($x, $h)
    {

        $c            = new Cell();
        $c->text      = $this->commande['DateExpedition'];
        $c->font      = new Font('bagc-bold', $this->layout->cExpeditionDateFontSize);
        $c->border    = true;
        $c->fill      = true;
        $c->fillColor = new FillColor(Color::greyscale(255));
        $c->align     = Cell::ALIGN_CENTER;
        $c->vAlign    = Cell::VALIGN_CENTER;
        $c->textColor = new TextColor(Color::cmyk(0, 100, 100, 0));
        $c->position  = new Position(
            $this->_x($x - $this->layout->cExpeditionDateWidth),
            $this->_y($this->layout->hBloc() - $h * 2)
        );
        $c->width     = $this->layout->cExpeditionDateWidth;
        $c->height    = $h;

        if ($this->commande['EstImperatif'] || substr($this->commande['CodeProduit'], -4) == 'RUSH') {
            $c->textColor->color = Color::cmyk(0, 0, 100, 0);
            $c->fillColor->color = Color::cmyk(0, 100, 100, 0);
        }
        $c->draw($this->pdf);
    }

    protected function livraisonCodePays($x, $h)
    {
        $c            = new Cell();
        $c->text      = $this->commande['CodePays'];
        $c->textColor = new TextColor(Color::greyscale(0));
        $c->font      = new Font('bagc-bold', 18);
        $c->border    = true;
        $c->height    = $h;
        $c->align     = Cell::ALIGN_CENTER;
        $c->width     = $this->layout->cLivraisonCodePaysWidth;
        $c->position  = new Position(
            $this->_x($x - $this->layout->cLivraisonCodePaysWidth),
            $this->_y($this->layout->hBloc() - $h)
        );
        $c->draw($this->pdf);

    }

    protected function grille()
    {
        for ($i = 0; $i < $this->layout->grilleRowCount(); $i++) {
            for ($j = 0; $j < $this->layout->grilleColCount(); $j++) {
                $this->cellule($i, $j);
            }
        }
    }

    protected function cellule($i, $j)
    {
        $offsetX = $this->layout->wBloc() - $this->layout->cellule() * $this->layout->grilleColCount();
        $offsetY = $this->layout->hBloc() - $this->layout->cellule() * $this->layout->grilleRowCount();
        $p       = new Position($this->_x($offsetX + $j * $this->layout->cellule()), $this->_y($offsetY + $i * $this->layout->cellule()));

        if ($class = $this->layout->cGrilleCellules[$i][$j]) {
            $class = '\Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule\\' . $class;
            if (class_exists($class)) {
                $ref     = new \ReflectionClass($class);
                $cellule = $ref->newInstance();
                $cellule->draw(
                    $p,
                    $this->pdf,
                    $this->layout->cellule(),
                    $this->commande
                );
            }
        } else {
            CelluleHelper::drawEmptyCell($p, $this->pdf, $this->layout->cellule());
        }
    }
}
