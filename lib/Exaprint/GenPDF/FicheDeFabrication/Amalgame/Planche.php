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
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Image;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Rect;
use Exaprint\TCPDF\RoundedRect;
use Exaprint\TCPDF\Text;
use Exaprint\TCPDF\TextColor;
use Exaprint\TCPDF\TextShadow;

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

    public function getBlocs()
    {
        $lignes = [];

        $lightBlack  = new Font('bagc-light', 36, new TextColor(Color::black()));
        $mediumWhite = new Font('bagc-medium', 28, new TextColor(Color::white()));

        $lignes[0] = [
            "height" => 18,
            "blocs"  => [
                [
                    "label" => "N° Planche",
                    "value" => number_format($this->planche['IDPlanche'], 0, '.', ' '),
                    "width" => $this->layout->pEnteteIdPlancheWidth
                ],
                [
                    "label" => "Expé SANS façonnage",
                    "value" => $this->planche['ExpeSansFaconnage'],
                    "width" => $this->layout->pEnteteExpeSansFaconnageWidth
                ],
                [
                    "label" => "Expé AVEC façonnage",
                    "value" => $this->planche['ExpeAvecFaconnage'],
                    "width" => $this->layout->pEnteteExpeAvecFaconnageWidth
                ],
                [
                    "label" => "Impératifs",
                    "value" => $this->countImperatifs(),
                    "width" => $this->layout->pEnteteImperatifsWidth,
                    "valueFont" => new Font('bagc-bold', 36, new TextColor(Color::cmyk(0, 100, 75, 0))),
                ],
                [
                    "value" => $this->planche['EstSousTraitance'],
                    "width" => 20
                ],
                [
                    "callback" => 'codeBarre',
                    "width"    => 40
                ],
                [
                    "label" => "Nb commandes",
                    "value" => $this->countCommandes(),
                    "width" => $this->layout->pEnteteNbCommandesWidth
                ],
            ]
        ];

        $lignes[1] = [
            "height" => 16,
            "blocs"  => [
                [
                    "value"     => $this->formatNbFeuilles(),
                    "width"     => 30,
                    "valueFont" => new Font('bagc-bold', 36, new TextColor(Color::white())),
                    "fillColor" => new FillColor(Color::black()),
                ],
                [
                    "value"     => $this->getFormat(),
                    "valueFont" => $lightBlack,
                    "width"     => 30,
                ],
                [
                    "callback" => "couleurs",
                    "width"    => 16,
                ],
                [
                    "image" => $this->getImpressionRectoVerso(),
                    "width" => 16,
                ],
                [
                    "value" => _('bascule_' . $this->planche['Bascule']),
                    "valueFont" => new Font('bagc-light', 26, new TextColor(Color::black())),
                    "width" => 16,
                ],
                [
                    "value"     => _('valeur_' . $this->planche['Support']),
                    "valueFont" => new Font('bagc-light', 26, new TextColor(Color::black())),
                    "width"     => 92,
                    "fillColor" => new FillColor(Color::cmyk(0, 0, 75, 0)),
                ]
            ],

        ];

        $lignes[2] = [
            "height" => 12,
            "blocs"  => [
                [
                    "value"     => "PELL",
                    "width"     => 22,
                    "fillColor" => new FillColor(Color::black()),
                    "valueFont" => $mediumWhite
                ],
                [
                    "value"     => $this->getPelliculage(),
                    "width"     => 78,
                    "valueFont" => new Font('bagc-light', 30, new TextColor(Color::black())),
                    "fillColor" => new FillColor(Color::cmyk(0, 100, 80, 0)),
                ],

                [
                    "value"     => "VERN",
                    "width"     => 22,
                    "fillColor" => new FillColor(Color::black()),
                    "valueFont" => $mediumWhite
                ],
                [
                    "value"     => $this->getVernis(),
                    "width"     => 78,
                    "valueFont" => new Font('bagc-light', 30, new TextColor(Color::black()))
                ],
            ]
        ];

        $lignes[3] = [
            "height" => "12",
            "blocs"  => [

                [
                    "value"     => "UV",
                    "width"     => 22,
                    "fillColor" => new FillColor(Color::black()),
                    "valueFont" => $mediumWhite
                ],
                [
                    "value"     => $this->getVernisUV(),
                    "width"     => 78,
                    "valueFont" => new Font('bagc-light', 30, new TextColor(Color::black())),
                    "fillColor" => new FillColor(Color::cmyk(40, 80, 0, 0)),
                ],
                [
                    "value"     => "DOR",
                    "width"     => 22,
                    "fillColor" => new FillColor(Color::black()),
                    "valueFont" => $mediumWhite
                ],
                [
                    "value"     => ($this->planche['DorureRecto']) ? _('valeur_' . $this->planche['DorureRecto']) : null,
                    "width"     => 78,
                    "valueFont" => new Font('bagc-light', 30, new TextColor(Color::black()))
                ],
            ]
        ];

        $lignes[4] = [
            "height" => 12,
            "blocs"  => [
                [
                    "value"     => "FAÇO",
                    "width"     => 22,
                    "fillColor" => new FillColor(Color::black()),
                    "valueFont" => $mediumWhite
                ],
                [
                    "callback" => "faconnage",
                    "width"    => 178
                ]
            ]
        ];

        return $lignes;
    }


    public function draw()
    {
        $y       = $this->layout->soucheHeight;
        $default = [
            "label"     => "",
            "value"     => "",
            "width"     => 0,
            "valueFont" => new Font('bagc-bold', 36, new TextColor(Color::black())),
            "labelFont" => new Font('bagc-light', 10, new TextColor(Color::greyscale(40))),
            "fillColor" => new FillColor(Color::white())
        ];
        foreach ($this->getBlocs() as $ligne) {
            $x = $this->layout->marge;
            foreach ($ligne['blocs'] as $bloc) {
                $p = new Position($x, $y);
                $d = new Dimensions($bloc['width'], $ligne['height']);
                if (isset($bloc['callback'])) {
                    call_user_func([$this, $bloc['callback']], $p, $d);
                } else if (isset($bloc['image'])) {
                    $this->image(
                        $p,
                        $d,
                        $bloc['image']
                    );
                } else {
                    $bloc = array_merge($default, $bloc);
                    $this->cell(
                        $p,
                        $d,
                        $bloc['label'],
                        $bloc['value'],
                        $bloc['valueFont'],
                        $bloc['labelFont'],
                        $bloc['fillColor']
                    );
                }

                $x += $bloc['width'];
            }
            $y += $ligne['height'] + 3;
        }


        foreach ($this->getIndicationsCommandes() as $type => $pages) {
            if(count($pages)){
                $cell = new Cell();
                $cell->border = 1;
                $cell->position = new Position($this->layout->marge, $y);
                $cell->text= $type;
                $cell->width = 22;
                $cell->height = 12;

                $cell->font = new Font('bagc-medium', 12, new TextColor(Color::black()));
                $cell->draw($this->pdf);
            }
            $x = $this->layout->marge + 24;
            foreach ($pages as $numeroDePage => $details) {
                $this->drawIndicationCommandesPage(
                    new Position($x, $y),
                    $numeroDePage,
                    $details,
                    Color::cmyk(0, 100, 75, 0)
                );
                $x+= 7;
            }
            if(count($pages)) $y += 12;
        }
    }

    public function drawIndicationCommandesPage(
        Position $p,
        $numeroDePage,
        $sections = [],
        Color $fillColor = null
    )
    {
        $p->y+=0.5;
        $w = 5.778;
        $h = 8.172;

        $rect = new Rect();
        $rect->position = $p;
        $rect->dimensions = new Dimensions($w/2, $h/2);
        $rect->style = Rect::STYLE_FILL_THEN_STROKE;
        $rect->fillColor = new FillColor(isset($sections[0]) ? $fillColor : Color::white());
        $rect->draw($this->pdf);

        $rect = new Rect();
        $rect->position = $p->add(new Position($w/2, 0));
        $rect->dimensions = new Dimensions($w/2, $h/2);
        $rect->style = Rect::STYLE_FILL_THEN_STROKE;
        $rect->fillColor = new FillColor(isset($sections[1]) ? $fillColor : Color::white());
        $rect->draw($this->pdf);

        $rect = new Rect();
        $rect->position = $p->add(new Position(0, $h/2));
        $rect->dimensions = new Dimensions($w/2, $h/2);
        $rect->style = Rect::STYLE_FILL_THEN_STROKE;
        $rect->fillColor = new FillColor(isset($sections[2]) ? $fillColor : Color::white());
        $rect->draw($this->pdf);

        $rect = new Rect();
        $rect->position = $p->add(new Position($w/2, $h/2));
        $rect->dimensions = new Dimensions($w/2, $h/2);
        $rect->style = Rect::STYLE_FILL_THEN_STROKE;
        $rect->fillColor = new FillColor(isset($sections[3]) ? $fillColor : Color::white());
        $rect->draw($this->pdf);

        $cell = new Cell();
        $cell->position = $p->add(new Position(0, $h));
        $cell->width = $w;
        $cell->height = 4;
        $cell->align = Cell::ALIGN_CENTER;
        $cell->text = $numeroDePage;
        $cell->border = 0;
        $cell->font = new Font('bagc-bold', 10, new TextColor(Color::black()));
        $cell->ignoreMinHeight = true;
            $cell->draw($this->pdf);
    }

    public function image(Position $p, Dimensions $d, $src)
    {
        $rect             = new Rect();
        $rect->dimensions = $d;
        $rect->position   = $p;
        $rect->style      = Rect::STYLE_STROKE;
        $rect->draw($this->pdf);

        $image         = new Image();
        $image->height = $d->height - 2;
        $image->width  = $d->width - 2;
        $image->file   = $src;
        $image->x      = $p->x + 1;
        $image->y      = $p->y + 1;
        $image->draw($this->pdf);
    }

    public function cell(Position $p, Dimensions $d, $label, $value, $valueFont, $labelFont, $fillColor)
    {
        if (is_numeric($value) && $value == 0)
            $value = '';


        $cell                  = new Cell();
        $cell->position        = $p;
        $cell->width           = $d->width;
        $cell->height          = $d->height;
        $cell->text            = $value;
        $cell->align           = Cell::ALIGN_CENTER;
        $cell->vAlign          = ($label) ? Cell::VALIGN_BOTTOM : Cell::VALIGN_CENTER;
        $cell->ignoreMinHeight = true;
        $cell->font            = $valueFont;
        $cell->fill            = ($value);
        $cell->border          = true;
        $cell->fillColor       = $fillColor;
        $cell->draw($this->pdf);

        if ($label) {
            $labelText = new Text($p->x, $p->y, $label, $labelFont);
            $labelText->draw($this->pdf);
        }

        if (!$value) {
            $this->pdf->Line($p->x, $p->y, $d->width + $p->x, $d->height + $p->y);
            $this->pdf->Line($d->width + $p->x, $p->y, $p->x, $d->height + $p->y);
        }
    }


    protected function countImperatifs()
    {
        $cnt = 0;
        foreach ($this->planche['commandes'] as $commande) {
            if ($commande['EstImperatif']) $cnt++;
        }
        return $cnt;
    }

    protected function countCommandes()
    {
        return count($this->planche['commandes']);
    }

    protected function codeBarre(Position $position, Dimensions $dimensions)
    {
        $margin = $this->layout->pCodeBarreMargin;
        $this->pdf->Rect($position->x, $position->y, $dimensions->width, $dimensions->height, 's');
        $this->pdf->write1DBarcode(
            $this->planche['IDPlanche'],
            'C39',
            $position->x + $margin / 2,
            $position->y + $margin / 2,
            $dimensions->width - $margin,
            $dimensions->height - $margin,
            0.4,
            '',
            'N'
        );
    }

    protected function formatNbFeuilles()
    {
        return number_format($this->planche['NbFeuilles'], 0, '.', ' ');
    }

    protected function getFormat()
    {
        return $this->planche['Largeur'] . '×' . $this->planche['Longueur'];
    }

    protected function getImpressionRectoVerso()
    {
        if ($this->planche['ImpressionVerso']) {
            return '../assets/RectoVerso.png';
        }
        return '../assets/Recto.png';
    }

    protected function getPelliculage()
    {
        if (is_null($this->planche['PelliculageRecto'])) return null;

        $txt = _('valeur_' . $this->planche['PelliculageRecto']) . ' R°';

        if ($this->planche['PelliculageVerso']) {
            $txt .= 'V°';
        }

        return $txt;
    }

    protected function getVernis()
    {
        if (is_null($this->planche['VernisRecto'])) return null;

        $txt = _('valeur_' . $this->planche['VernisRecto']) . ' R°';

        if ($this->planche['VernisVerso']) {
            $txt .= 'V°';
        }

        return $txt;
    }

    protected function getVernisUV()
    {
        if (is_null($this->planche['VernisSelectifRecto'])) return null;

        $txt = _('valeur_' . $this->planche['VernisSelectifRecto']);

        if ($this->planche['VernisSelectifVerso']) {
            $txt .= ' RV';
        }

        return $txt;
    }

    protected function couleurs(Position $position, Dimensions $dimensions)
    {

        $rect             = new Rect();
        $rect->dimensions = $dimensions;
        $rect->position   = $position;
        $rect->style      = Rect::STYLE_STROKE;
        $rect->draw($this->pdf);

        $image         = new Image();
        $image->height = $dimensions->height - 2;
        $image->width  = $dimensions->width - 2;
        $image->file   = '../assets/Quadri.png';
        $image->x      = $position->x + 1;
        $image->y      = $position->y + 1;
        $image->draw($this->pdf);


    }

    protected function faconnage(Position $position, Dimensions $dimensions)
    {
        $rect             = new Rect();
        $rect->dimensions = $dimensions;
        $rect->position   = $position;
        $rect->style      = Rect::STYLE_STROKE;
        $rect->draw($this->pdf);

        $margin  = 1;
        $pMargin = new Position($margin, $margin);

        if ($this->planche['Pliage'] || $this->planche['AvecPliage']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(18, $dimensions->height - $margin * 2), // todo width en clé de trad ?
                'Pliage',
                Color::cmyk(25, 0, 100, 60)
            );
            $position = $position->add(new Position(18 + $margin, 0));
        }

        if ($this->planche['Rainage'] || $this->planche['AvecRainage']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(20, $dimensions->height - $margin * 2),
                'Rainage',
                Color::cmyk(0, 100, 75, 20)
            );
            $position = $position->add(new Position(20 + $margin, 0));
        }

        if ($this->planche['Decoupe'] || $this->planche['AvecDecoupe']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(20, $dimensions->height - $margin * 2),
                'Découpe',
                Color::cmyk(50, 0, 75, 75)
            );
            $position = $position->add(new Position(20 + $margin, 0));

        }

        if ($this->planche['Predecoupe']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(30, $dimensions->height - $margin * 2),
                'Prédécoupe',
                Color::cmyk(80, 0, 30, 20)
            );

            $position = $position->add(new Position(30 + $margin, 0));
        }

        if ($this->planche['DecoupeALaForme']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(42, $dimensions->height - $margin * 2),
                'Découpe à la forme',
                Color::cmyk(80, 0, 100, 10)
            );
            $position = $position->add(new Position(42 + $margin, 0));
        }

        if ($this->planche['Perforation'] || $this->planche['AvecPerforation']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(30, $dimensions->height - $margin * 2),
                'Perforation',
                Color::cmyk(0, 30, 10, 80)
            );
            $position = $position->add(new Position(30 + $margin, 0));
        }

        if ($this->planche['Encollage'] || $this->planche['AvecEncollage']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(30, $dimensions->height - $margin * 2),
                'Encollage',
                Color::cmyk(0, 30, 10, 80)
            );
            $position = $position->add(new Position(30 + $margin, 0));
        }
        if ($this->planche['NbCoinsRonds']) {
            $this->tagFaconnage(
                $position->add($pMargin),
                new Dimensions(30, $dimensions->height - $margin * 2),
                'Coins Ronds ' . $this->planche['NbCoinsRonds'],
                Color::cmyk(0, 30, 10, 80)
            );
            $position = $position->add(new Position(30 + $margin, 0));
        }
    }

    protected function tagFaconnage(Position $position, Dimensions $dimensions, $txt, Color $color)
    {
        $rr             = new RoundedRect();
        $rr->dimensions = $dimensions;
        $rr->position   = $position;
        $rr->radius     = 3;
        $rr->fillColor  = new FillColor($color);
        $rr->style      = Rect::STYLE_FILL;
        $rr->draw($this->pdf);

        $cell           = new Cell();
        $cell->position = $position;
        $cell->text     = $txt;
        $cell->width    = $dimensions->width;
        $cell->align    = Cell::ALIGN_CENTER;
        $cell->vAlign   = Cell::VALIGN_CENTER;
        $cell->height   = $dimensions->height;
        $cell->font     = new Font('bagc-light', 20, new TextColor(Color::white()));
        $cell->fill     = false;
        $cell->draw($this->pdf);
    }

    protected function getIndicationsCommandes()
    {
        $result = [
            'BAT'          => [],
            'Justificatif' => [],
            'Commentaires' => [],
            'Retirage'     => [],
        ];
        foreach ($this->planche['commandes'] as $i => $commande) {
            $page     = (int)ceil(($i + 3) / 4);
            $position = (int)(($i + 2) % 4);

            if ($commande['BAT']) {
                if (!isset($result['BAT'][$page])) $result['BAT'][$page] = [];
                $result['BAT'][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }

            if ($commande['IDCommandePrincipale']) {
                if (!isset($result['Retirage'][$page])) $result['Retirage'][$page] = [];
                $result['Retirage'][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }
            if ($commande['Justificatif']) {
                if (!isset($result['Justificatif'][$page])) $result['Justif'][$page] = [];
                $result['Justif'][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }
            if (trim($commande['CommentairePAO']) != '') {
                if (!isset($result['CommentairePAO'][$page])) $result['Com. PAO'][$page] = [];
                $result['Com. PAO'][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }
            if ($commande['NbPorteCarte']) {
                if (!isset($result['NbPorteCarte'][$page])) $result['P.C.'][$page] = [];
                $result['P.C.'][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }
        }

        return $result;
    }
}