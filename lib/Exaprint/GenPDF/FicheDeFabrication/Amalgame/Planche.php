<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19/02/2014
 * Time: 11:34
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;

use Exaprint\GenPDF\FicheDeFabrication\Amalgame;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\CellPadding;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Text;
use Exaprint\TCPDF\TextColor;

class Planche
{


    /**
     * @var \TCPDF
     */
    public $pdf;

    protected $planche;
    /**
     * @var Layout
     */
    protected $layout;

    function __construct(\TCPDF $pdf, $planche, Layout $layout)
    {
        $this->pdf     = $pdf;
        $this->planche = $planche;
        $this->layout  = $layout;
    }

    public function draw()
    {
        $this->entete();
        $this->production();
        $this->border();
    }

    protected function border()
    {
        $this->pdf->Rect(
            $this->layout->xBloc(0),
            $this->layout->yBloc(0),
            $this->layout->wRang(),
            $this->layout->hRang()
        );
    }

    protected function entete()
    {
        $this->idPlanche();
        $this->expeSansFaconnage();
        $this->expeAvecFaconnage();
        $this->imperatifs();
        $this->codeBarre();
        $this->nbCommandes();
    }

    protected function idPlanche()
    {
        $this->enteteCellule(
            $this->layout->marge,
            $this->layout->pEnteteIdPlancheWidth,
            'N° Planche',
            number_format($this->planche['IDPlanche'], '0', '', ' ')
        );
    }


    protected function enteteCellule($x, $width, $label, $value, $valueFont = null)
    {
        if (is_null($valueFont)) {
            $valueFont = new Font('bagc-bold', 36, new TextColor(Color::greyscale(0)));
        }

        $cell                  = new Cell();
        $cell->font            = $valueFont;
        $cell->position        = new Position($x, $this->layout->soucheHeight);
        $cell->text            = $value;
        $cell->height          = $this->layout->pEnteteHeight;
        $cell->ignoreMinHeight = true;
        $cell->width           = $width;
        $cell->border          = 1;
        $cell->vAlign          = Cell::VALIGN_BOTTOM;
        $cell->align           = Cell::ALIGN_CENTER;
        $cell->draw($this->pdf);


        $txt = new Text(
            $cell->position->x,
            $cell->position->y,
            $label,
            new Font('bagc-light', 10, new TextColor(Color::greyscale(100)))
        );

        $txt->draw($this->pdf);
    }


    protected function expeSansFaconnage()
    {
        $this->enteteCellule(
            $this->layout->marge + $this->layout->pEnteteIdPlancheWidth,
            $this->layout->pEnteteExpeSansFaconnageWidth,
            'Expé sans façonnage',
            $this->planche['ExpeSansFaconnage']
        );
    }

    protected function expeAvecFaconnage()
    {
        $this->enteteCellule(
            $this->layout->marge + $this->layout->pEnteteIdPlancheWidth + $this->layout->pEnteteExpeSansFaconnageWidth,
            $this->layout->pEnteteExpeSansFaconnageWidth,
            'Expé avec façonnage',
            $this->planche['ExpeAvecFaconnage']
        );
    }

    protected function imperatifs()
    {
        $cnt = $this->countImperatifs();
        $this->enteteCellule(
            $this->layout->marge + $this->layout->pEnteteIdPlancheWidth + $this->layout->pEnteteExpeSansFaconnageWidth + $this->layout->pEnteteExpeAvecFaconnageWidth,
            $this->layout->pEnteteImperatifsWidth,
            'Impératifs',
            $cnt,
            new Font('bagc-bold', 36, new TextColor(($cnt) ? Color::cmyk(0, 100, 100, 0) : Color::greyscale(0)))
        );
    }

    protected function countImperatifs()
    {
        $cnt = 0;
        foreach ($this->planche['commandes'] as $commande) {
            if ($commande['EstImperatif']) $cnt++;
        }
        return $cnt;
    }

    protected function nbCommandes()
    {
        $this->enteteCellule(
            $this->layout->marge + $this->layout->wRang() - $this->layout->pEnteteNbCommandesWidth,
            $this->layout->pEnteteNbCommandesWidth,
            'Nb. commandes',
            count($this->planche['commandes'])
        );
    }

    protected function codeBarre()
    {
        $this->enteteCellule(
            $this->layout->marge + $this->layout->wRang() - $this->layout->pEnteteNbCommandesWidth - $this->layout->pCodeBarreWidth,
            $this->layout->pCodeBarreWidth,
            '',
            ''
        );
        $this->pdf->write1DBarcode(
            $this->planche['IDPlanche'],
            'C39',
            $this->layout->marge + $this->layout->wRang() - $this->layout->pEnteteNbCommandesWidth - $this->layout->pCodeBarreWidth + $this->layout->pCodeBarreMargin,
            $this->layout->soucheHeight + $this->layout->pCodeBarreMargin,
            $this->layout->pCodeBarreWidth - $this->layout->pCodeBarreMargin * 2,
            $this->layout->pEnteteHeight - $this->layout->pCodeBarreMargin * 2,
            0.4,
            '',
            'N'
        );
    }


    protected function production()
    {
        $quant = new Cell();
        $quant->fill = true;
        $quant->font = new Font('bagc-bold', 36);
        $quant->align = Cell::ALIGN_CENTER;
        $quant->vAlign = Cell::VALIGN_BOTTOM;
        $quant->fillColor = new FillColor(Color::cmyk(0, 75, 100, 0));
        $quant->textColor = new TextColor(Color::greyscale(255));
        $quant->text = $this->planche['NbFeuilles'];
        $quant->height = $this->layout->pEnteteHeight;
        $quant->width = 40;
        $quant->border = 1;
        $quant->position = new Position(
            $this->layout->marge,
            $this->layout->soucheHeight + $this->layout->pEnteteHeight
        );


        $quant->draw($this->pdf);
    }

    protected function getLigneY($name)
    {
        print_r([
            "name"  => $name,
            "index" => array_search($name, $this->layout->pRangs),
            "y"     => array_search($name, $this->layout->pRangs) * $this->layout->pRangHeight + $this->layout->soucheHeight + $this->layout->pEnteteHeight,
        ]);
        if (($index = array_search($name, $this->layout->pRangs)) !== false) {
            return $index * $this->layout->pRangHeight + $this->layout->soucheHeight + $this->layout->pEnteteHeight;
        }

        return 0;
    }

    protected function groupe($y, $label, Font $font = null, FillColor $fillColor = null)
    {
        if (is_null($font)) {
            $font = new Font('bagc-bold', 12, new TextColor(Color::greyscale(255)));
        }

        if (is_null($fillColor)) {
            $fillColor = new FillColor(Color::greyscale(100));
        }

        $cell              = new Cell();
        $cell->font        = $font;
        $cell->position    = new Position($this->layout->marge, $y);
        $cell->text        = $label;
        $cell->border      = 1;
        $cell->height      = $this->layout->pRangHeight;
        $cell->width       = $this->layout->pRangGroupeWidth;
        $cell->align       = Cell::ALIGN_LEFT;
        $cell->vAlign      = Cell::VALIGN_CENTER;
        $cell->fill        = true;
        $cell->fillColor   = $fillColor;
        $cell->cellPadding = CellPadding::left(2);
        $cell->draw($this->pdf);
    }

    protected function cellule(Position $position, $label, $value, $width, $labelFont = null, $valueFont = null, $fillColor = null)
    {
        if (is_null($labelFont)) {
            $labelFont = new Font('bagc-light', 10, new TextColor(Color::greyscale(100)));
        }

        if (is_null($valueFont)) {
            $valueFont = new Font('bagc-medium', 18, new TextColor(Color::greyscale(0)));
        }

        if (is_null($fillColor)) {
            $fillColor = new FillColor(Color::greyscale(255));
        }

        $cell                  = new Cell();
        $cell->font            = $valueFont;
        $cell->position        = $position;
        $cell->text            = $value;
        $cell->border          = 1;
        $cell->height          = $this->layout->pRangHeight;
        $cell->ignoreMinHeight = true;
        $cell->width           = $width;
        $cell->align           = Cell::ALIGN_CENTER;
        $cell->vAlign          = Cell::VALIGN_BOTTOM;
        $cell->fill            = true;
        $cell->fillColor       = $fillColor;
        $cell->cellPadding     = CellPadding::bottom(0.5);
        $cell->draw($this->pdf);

        $txt = new Text(
            $cell->position->x,
            $cell->position->y,
            $label,
            $labelFont
        );

        $txt->draw($this->pdf);

        if (!$value) {
            $this->pdf->SetLineStyle(['color' => [150]]);
            $this->pdf->Line(
                $cell->position->x,
                $cell->position->y + $cell->height,
                $cell->position->x + $cell->width,
                $cell->position->y
            );
            $this->pdf->Line(
                $cell->position->x,
                $cell->position->y,
                $cell->position->x + $cell->width,
                $cell->position->y + $cell->height
            );
            $this->pdf->SetLineStyle(['color' => [0]]);
        }

    }

    protected function support()
    {
        $x = $this->layout->marge + $this->layout->pRangGroupeWidth;
        $y = $this->getLigneY('support');

        $wNbFeuilles = 30;
        $wFormat     = 30;
        $wGrammage   = 15;
        $wPapier     = 95;
        $this->groupe($y, 'Support');
        $this->cellule(
            new Position($x, $y),
            "Nb feuilles",
            number_format(1000, 0, ',', ' '),
            $wNbFeuilles
        );
        $this->cellule(
            new Position($x + $wNbFeuilles, $y),
            "Format",
            "52 × 74",
            $wFormat
        );
        $this->cellule(
            new Position($x + $wNbFeuilles + $wFormat, $y),
            "Grammage",
            "350",
            $wGrammage
        );
        $this->cellule(
            new Position($x + $wNbFeuilles + $wFormat + $wGrammage, $y),
            "Papier",
            "CM Condat Périgord",
            $wPapier
        );
    }

    protected function impression()
    {
        $x = $this->layout->marge + $this->layout->pRangGroupeWidth;
        $y = $this->getLigneY('impression');

        $wRecto   = 65;
        $wVerso   = 65;
        $wBascule = 40;

        $this->groupe($y, 'Impression');
        $this->cellule(
            new Position($x, $y),
            'Recto',
            'quadri',
            $wRecto
        );
        $this->cellule(
            new Position($x + $wRecto, $y),
            'Verso',
            'quadri',
            $wVerso
        );
        $this->cellule(
            new Position($x + $wRecto + $wVerso, $y),
            'Bascule',
            'RV 8 coul',
            $wBascule
        );
    }

    protected function pelliculage()
    {
        $this->caracteristiqueRectoVerso(
            $this->getLigneY('pelliculage'),
            'Pelliculage',
            'Mat',
            'Mat'
        );

    }

    protected function vernis()
    {
        $this->caracteristiqueRectoVerso(
            $this->getLigneY('vernis'),
            'Vernis',
            'Acrylique Satiné',
            ''
        );
    }

    protected function vernisSelectif()
    {
        $this->caracteristiqueRectoVerso(
            $this->getLigneY('vernisSelectif'),
            'Vernis Sélectif',
            'Vernis UV Sélectif 3D',
            'Vernis UV Sélectif 3D'
        );
    }

    protected function faconnage()
    {
        $x = $this->layout->marge + $this->layout->pRangGroupeWidth;
        $y = $this->getLigneY('faconnage');
        $this->groupe($y, 'Façonnage');
        $wPliage     = 25;
        $wRainage    = 25;
        $wPerfo      = 35;
        $wDecoupe    = 40;
        $wPredecoupe = 30;
        $wCoinsRonds = 15;

        $this->cellule(new Position($x, $y), 'Pliage', 'OUI', $wPliage);
        $x += $wPliage;
        $this->cellule(new Position($x, $y), 'Rainage', 'OUI', $wRainage);
        $x += $wRainage;
        $this->cellule(new Position($x, $y), 'Perforation', '', $wPerfo);
        $x += $wPerfo;
        $this->cellule(new Position($x, $y), 'Découpe', '', $wDecoupe);
        $x += $wDecoupe;
        $this->cellule(new Position($x, $y), 'Pré-découpe', '', $wPredecoupe);
        $x += $wPredecoupe;
        $this->cellule(new Position($x, $y), 'Coins ronds', '8', $wCoinsRonds);
    }

    protected function caracteristiqueRectoVerso($y, $label, $valeurRecto, $valeurVerso)
    {
        $x = $this->layout->marge + $this->layout->pRangGroupeWidth;
        $w = ($this->layout->wRang() - $this->layout->pRangGroupeWidth) / 2;
        $this->groupe($y, $label);
        $this->cellule(
            new Position($x, $y),
            'Recto',
            $valeurRecto,
            $w
        );
        $this->cellule(
            new Position($x + $w, $y),
            'Verso',
            $valeurVerso,
            $w
        );

    }

    protected function observations()
    {
        $position = new Position(
            $this->layout->marge + $this->layout->wRang() - $this->layout->pObservationsWidth,
            $this->layout->soucheHeight + $this->layout->hRang() - $this->layout->pObservationsHeight
        );

        $cell         = new MultiCell();
        $cell->x      = $position->x;
        $cell->y      = $position->y;
        $cell->border = 1;
        $cell->width  = $this->layout->pObservationsWidth;
        $cell->height = $this->layout->pObservationsHeight;
        $cell->text   = 'DÉCOUPE OUTIL envoyée par Formazur';
        $cell->font   = new Font('bagc-medium', 12, new TextColor(Color::cmyk(0, 100, 100, 0)));
        $cell->draw($this->pdf);
    }

} 