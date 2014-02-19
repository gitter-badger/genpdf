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
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Text;
use Exaprint\TCPDF\TextColor;

class Planche
{


    public static $width = 200;
    public static $height = 142;

    public static $hEntete = 16;
    public static $wIdPlanche = 50;
    public static $wExpeSansFaconnage = 30;
    public static $wExpeAvecFaconnage = 30;
    public static $wImperatifs = 20;
    public static $wNbCommandes = 20;
    public static $hRang = 10;
    public static $wGroupe = 30;

    public static $wCodeBarre = 30;
    public static $mCodeBarre = 2;

    public static $lignes = [
        "support",
        "impression",
        "pelliculage",
        "vernis",
        "vernisSelectif",
        "faconnage"
    ];

    /**
     * @var \TCPDF
     */
    public $pdf;

    protected $planche;

    function __construct(\TCPDF $pdf, $planche)
    {
        $this->pdf     = $pdf;
        $this->planche = $planche;
    }

    public function draw()
    {
        $this->entete();
        $this->support();
        $this->impression();
        $this->pelliculage();
        $this->vernis();
        $this->vernisSelectif();
        $this->faconnage();
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
            Amalgame::$marge,
            self::$wIdPlanche,
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
        $cell->position        = new Position($x, Amalgame::$hSouche);
        $cell->text            = $value;
        $cell->height          = self::$hEntete;
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
            Amalgame::$marge + self::$wIdPlanche,
            self::$wExpeSansFaconnage,
            'Expé sans façonnage',
            '12/04'
        );
    }

    protected function expeAvecFaconnage()
    {
        $this->enteteCellule(
            Amalgame::$marge + self::$wIdPlanche + self::$wExpeSansFaconnage,
            self::$wExpeSansFaconnage,
            'Expé avec façonnage',
            '13/04'
        );
    }

    protected function imperatifs()
    {
        $this->enteteCellule(
            Amalgame::$marge + self::$wIdPlanche + self::$wExpeSansFaconnage + self::$wExpeAvecFaconnage,
            self::$wImperatifs,
            'Impératifs',
            '4',
            new Font('bagc-bold', 36, new TextColor(Color::cmyk(0, 100, 100, 0)))
        );
    }

    protected function nbCommandes()
    {
        $this->enteteCellule(
            Amalgame::$marge + self::$width - self::$wNbCommandes,
            self::$wNbCommandes,
            'Nb. commandes',
            '54'
        );
    }

    protected function codeBarre()
    {
        $this->enteteCellule(
            Amalgame::$marge + self::$width - self::$wNbCommandes - self::$wCodeBarre,
            self::$wCodeBarre,
            '',
            ''
        );
        $this->pdf->write1DBarcode(
            $this->planche['IDPlanche'],
            'C39',
            Amalgame::$marge + self::$width - self::$wNbCommandes - self::$wCodeBarre + self::$mCodeBarre,
            Amalgame::$hSouche + self::$mCodeBarre,
            self::$wCodeBarre - self::$mCodeBarre * 2,
            self::$hEntete - self::$mCodeBarre * 2,
            0.4,
            '',
            'N'
        );
    }

    protected function getLigneY($name)
    {
        print_r([
            "name"  => $name,
            "index" => array_search($name, self::$lignes),
            "y"     => array_search($name, self::$lignes) * self::$hRang + Amalgame::$hSouche + self::$hEntete,
        ]);
        if (($index = array_search($name, self::$lignes)) !== false) {
            return $index * self::$hRang + Amalgame::$hSouche + self::$hEntete;
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

        $cell            = new Cell();
        $cell->font      = $font;
        $cell->position  = new Position(Amalgame::$marge, $y);
        $cell->text      = $label;
        $cell->border    = 1;
        $cell->height    = self::$hRang;
        $cell->width     = self::$wGroupe;
        $cell->align     = Cell::ALIGN_LEFT;
        $cell->vAlign    = Cell::VALIGN_CENTER;
        $cell->fill      = true;
        $cell->fillColor = $fillColor;
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
        $cell->height          = self::$hRang;
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

        if(!$value){
            $this->pdf->Line(
                $cell->position->x,
                $cell->position->y + $cell->height,
                $cell->position->x + $cell->width,
                $cell->position->y
            );
        }

    }

    protected function support()
    {
        $x = Amalgame::$marge + self::$wGroupe;
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
        $x = Amalgame::$marge + self::$wGroupe;
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
        $y = $this->getLigneY('faconnage');
    }

    protected function caracteristiqueRectoVerso($y, $label, $valeurRecto, $valeurVerso)
    {
        $x = Amalgame::$marge + self::$wGroupe;
        $w = (self::$width - self::$wGroupe) / 2;
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

} 