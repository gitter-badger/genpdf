<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Image;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Commande
{

    /**
     * @var \TCPDF
     */
    protected $pdf;

    public $x;

    public $y;

    public static $width = 100;

    public static $height = 142;

    public static $wIds = 32;
    public static $hIds = 10;
    public static $wQuantite = 28;

    public static $hVisuels = 69;
    public static $gCellSize = 9;
    public static $gRowCount = 7;
    public static $gColCount = 3;
    public static $wCodeBarre = 40;
    public static $hCodeBarre = 16;
    public static $pCodeBarre = 2;
    // W x H code postal
    public static $wCodePostal = 19;
    public static $hCodePostal = 9;
    // W x H code pays
    public static $wCodePays = 9;
    public static $hCodePays = 9;

    public static $cellules = [
        ['BatPapier', 'Justificatif', 'Retirage'],
        ['SousTraitance'],
        [],
        [],
        [],
        [],
        [],
    ];

    protected $commande;

    function __construct($commande, \TCPDF $pdf, $x, $y)
    {
        $this->pdf      = $pdf;
        $this->x        = $x;
        $this->y        = $y;
        $this->commande = $commande;

        $this->border();
        $this->ids();
        $this->commentairePao();
        $this->visuels();
        $this->formats();
        $this->quantite();
        $this->grille();
        $this->livraison();
        $this->referenceClient();
        $this->transporteur();
        $this->codeBarre();
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
            self::$width,
            self::$height
        );
    }

    protected function ids()
    {

        $w                = self::$wIds;
        $idCommandeHeight = 7;

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
        $idCommande->text            = $this->formatIdCommande();
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
        $idPlanche->height          = self::$hIds - $idCommandeHeight;
        $idPlanche->width           = $w;
        $idPlanche->text            = $this->formatIdPlanche();
        $idPlanche->fill            = true;
        $idPlanche->draw($this->pdf);

    }

    protected function formatIdCommande()
    {
        return preg_replace("#([0-9]{3})([0-9]{2})([0-9]{2})#", '$1 $2 $3', (string)$this->commande['IDCommande']);
    }

    protected function formatIdPlanche()
    {
        return number_format($this->commande['IDPlanche'], 0, '.', ' ');
    }

    protected function formatQuantite()
    {
        return number_format($this->commande['Quantite'], 0, '.', ' ');
    }

    protected function quantite()
    {
        $q                  = new Cell();
        $q->width           = self::$wQuantite;
        $q->font            = new Font('bagc-bold', 28);
        $q->fillColor       = new FillColor(Color::cmyk(100, 100, 0, 0));
        $q->textColor       = new TextColor(Color::greyscale(255));
        $q->text            = $this->formatQuantite();
        $q->position        = new Position($this->_x(self::$width - $q->width), $this->_y());
        $q->height          = self::$hIds;
        $q->align           = Cell::ALIGN_CENTER;
        $q->ignoreMinHeight = true;
        $q->vAlign          = Cell::VALIGN_BOTTOM;
        $q->fill            = true;
        $q->draw($this->pdf);
    }

    protected function formats()
    {
        $times                    = '×';
        $ouvert                   = $this->commande['Formats']['Ouvert'];
        $cOuvert                  = new Cell();
        $cOuvert->textColor       = new TextColor(Color::greyscale(0));
        $cOuvert->position        = new Position($this->_x(self::$wIds), $this->_y());
        $cOuvert->text            = $ouvert['Largeur'] . $times . $ouvert['Longueur'];
        $cOuvert->font            = new Font('bagc-bold', 28);
        $cOuvert->ignoreMinHeight = true;
        $cOuvert->border          = Cell::BORDER_NO_BORDER;
        $cOuvert->height          = self::$hIds;
        $cOuvert->width           = self::$width - self::$wQuantite - self::$wIds;
        $cOuvert->align           = Cell::ALIGN_CENTER;
        $cOuvert->vAlign          = Cell::VALIGN_BOTTOM;

        if (isset($this->commande['Formats']['Ferme'])) {

            $ferme = $this->commande['Formats']['Ferme'];

            $cOuvert->font   = new Font('bagc-bold', 18);
            $cOuvert->height = 6;

            $cFerme                  = new Cell();
            $cFerme->textColor       = new TextColor(Color::greyscale(0));
            $cFerme->position        = new Position($this->_x(self::$wIds), $this->_y(6));
            $cFerme->text            = $ferme['Largeur'] . $times . $ferme['Longueur'];
            $cFerme->font            = new Font('bagc-mediumital', 12);
            $cFerme->ignoreMinHeight = true;
            $cFerme->border          = Cell::BORDER_NO_BORDER;
            $cFerme->height          = 4;
            $cFerme->width           = self::$width - self::$wQuantite - self::$wIds;
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

        if (count($this->commande['Visuels']) == 1) {

            $this->placeImage(
                $this->commande['Visuels'][0],
                $this->_x(),
                $this->_y(self::$hIds),
                self::$width,
                self::$hVisuels
            );

        } else {

            $this->placeImage(
                $this->commande['Visuels'][0],
                $this->_x(),
                $this->_y(self::$hIds),
                self::$width / 2,
                self::$hVisuels
            );

            $this->placeImage(
                $this->commande['Visuels'][1],
                $this->_x(self::$width / 2),
                $this->_y(self::$hIds),
                self::$width / 2,
                self::$hVisuels
            );
        }

        $this->pdf->Rect(
            $this->_x(),
            $this->_y(self::$hIds),
            self::$width,
            self::$hVisuels
        );

    }

    protected function referenceClient()
    {
        $paddingTop         = 1;
        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y(self::$hIds + self::$hVisuels + $paddingTop);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = self::$width - self::$gCellSize * self::$gColCount;
        $c->text            = $this->commande['ReferenceClient'];
        $c->textColor       = new TextColor(Color::greyscale(0));
        $c->font            = new Font('bagc-medium', 14);
        $c->draw($this->pdf);
    }

    protected function commentairePao()
    {

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y(self::$hIds + self::$hVisuels + 10);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = self::$width - self::$gCellSize * self::$gColCount;
        $c->text            = $this->commande['CommentairePao'];
        $c->textColor       = new TextColor(Color::greyscale(0));
        $c->font            = new Font('bagc-light', 11);
        $c->draw($this->pdf);
    }

    protected function codeBarre()
    {
        $this->pdf->write1DBarcode(
            $this->commande['IDCommande'],
            'C39',
            $this->_x(self::$pCodeBarre),
            $this->_y(self::$height - self::$hCodeBarre - self::$pCodeBarre),
            self::$wCodeBarre,
            self::$hCodeBarre,
            0.4,
            '',
            'N'
        );
    }

    protected function transporteur()
    {

    }

    protected function livraison()
    {
        //Code Postal
        $c            = new Cell();
        $c->text      = $this->commande['CodePostalLivraison'];
        $c->textColor = new TextColor(Color::greyscale(0));
        $c->font      = new Font('bagc-light', 18);
        $c->border    = true;
        $c->height    = self::$hCodePostal;
        $c->align     = Cell::ALIGN_RIGHT;
        $c->width     = self::$wCodePostal;
        $c->position  = new Position(
            $this->_x(self::$width - self::$gCellSize * self::$gColCount - self::$wCodePays - self::$wCodePostal),
            $this->_y(self::$height - self::$hCodePostal)
        );
        $c->draw($this->pdf);

        //Code Pays
        $c            = new Cell();
        $c->text      = $this->commande['CodePaysLivraison'];
        $c->textColor = new TextColor(Color::greyscale(0));
        $c->font      = new Font('bagc-bold', 18);
        $c->border    = true;
        $c->height    = self::$hCodePostal;
        $c->align     = Cell::ALIGN_CENTER;
        $c->width     = self::$wCodePays;
        $c->position  = new Position(
            $this->_x(self::$width - self::$gCellSize * self::$gColCount - self::$wCodePays),
            $this->_y(self::$height - self::$hCodePays)
        );
        $c->draw($this->pdf);
    }

    protected function grille()
    {
        for ($i = 0; $i < self::$gRowCount; $i++) {
            for ($j = 0; $j < self::$gColCount; $j++) {
                $this->cellule($i, $j);
            }
        }
    }

    protected function cellule($i, $j)
    {
        $offsetX = self::$width - self::$gCellSize * self::$gColCount;
        $offsetY = self::$height - self::$gCellSize * self::$gRowCount;
        $p       = new Position($this->_x($offsetX + $j * self::$gCellSize), $this->_y($offsetY + $i * self::$gCellSize));

        if (isset(self::$cellules[$i][$j])) {
            $class = '\Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule\\' . self::$cellules[$i][$j];
            if (class_exists($class)) {
                $ref     = new \ReflectionClass($class);
                $cellule = $ref->newInstance();
                $cellule->draw(
                    $p,
                    $this->pdf,
                    self::$gCellSize,
                    $this->commande
                );
            }
        } else {
            $this->pdf->Rect($p->x, $p->y, self::$gCellSize, self::$gCellSize);
        }
    }
}
