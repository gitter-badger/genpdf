<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule\PEFC;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\FormeDeDecoupe;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Layout;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche2;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class Amalgame
{

    public static $wPage = 210;
    public static $hPage = 297;
    public static $gouttiere = 4;
    public static $marge = 5;
    public static $hSouche = 8;

    protected static $commandePositions = [
        ['x' => 5, 'y' => 8],
        ['x' => 105, 'y' => 8],
        ['x' => 5, 'y' => 150],
        ['x' => 105, 'y' => 150],
    ];

    public $pdf;
    protected $logger;
    protected $planche;
    protected $currentCommandePosition = 1;
    protected $layout;
    protected $pageNumber = 0;
    protected $_pageCount;

    protected $_formesDeDecoupe = [];

    public function __construct($planche)
    {

        $this->planche = $planche;

        $nbCommandes = count($this->planche['commandes']);

        $this->planche['estPEFC'] = false;
        foreach ($this->planche['commandes'] as &$c) {
            if ($c['Fichiers']['FormeDeDecoupe']) {
                $this->_formesDeDecoupe[] = [
                    'IDCommande' => $c['IDCommande'],
                    'Fichier'    => $c['Fichiers']['FormeDeDecoupe']
                ];

                $i = count($this->_formesDeDecoupe) - 1;

                $numero = (int)ceil(($nbCommandes + $i + 3) / 4);
                $index = (int)(($nbCommandes + $i + 2) % 4);

                $c['IndicateurFormeDeDecoupe'] = [
                    'index'  => $index,
                    'numero' => $numero,
                ];
            }
            if ($c['Certification'] == PEFC::CERTIFICATION_PEFC) {
                $this->planche['estPEFC'] = true;
            }
        }

        $this->pdf = new \TCPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );

        $this->layout = new Layout();

        $this->pdf->SetFont('bagc-reg');
        $this->pdf->SetMargins(0, 0, 0, true);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetAutoPageBreak(false);
        $this->newPage();

        $planchePdf = new Planche2(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );

        foreach ($this->planche['commandes'] as $commande) {
            $this->commande($commande);
        }

        foreach ($this->_formesDeDecoupe as $formeDeDecoupe) {
            $this->formeDecoupe($formeDeDecoupe['IDCommande'], $formeDeDecoupe['Fichier']);
        }

        var_dump($this->planche);
    }

    protected function newPage()
    {
        $this->pdf->AddPage();
        $this->souche();

        $this->pageNumber++;

        $cell           = new Cell();
        $cell->text     = $this->pageNumber . '/' . $this->getPageCount();
        $cell->font     = new Font('bagc-bold', 14, new TextColor(Color::black()));
        $cell->position = new Position($this->layout->pageWidth - $this->layout->marge - 20, $this->layout->pageHeight - 10);
        $cell->width    = 20;
        $cell->height   = 10;
        $cell->vAlign   = Cell::VALIGN_TOP;
        $cell->align    = Cell::ALIGN_CENTER;
        $cell->draw($this->pdf);
    }

    protected function getPageCount()
    {
        if (!isset($this->_pageCount)) {
            $nbCommandes      = count($this->planche['commandes']);
            $nbFormesDecoupe  = count($this->_formesDeDecoupe);
            $this->_pageCount = ceil(($nbCommandes + $nbFormesDecoupe + 2) / 4);
        }
        return $this->_pageCount;
    }

    protected function souche()
    {
        $this->pdf->Rect(0, 0, $this->layout->pageWidth, $this->layout->soucheHeight, '', ['ALL' => false]);
    }

    protected function commande($commande)
    {
        if ($this->currentCommandePosition < 3) {
            $this->currentCommandePosition++;
        } else {
            $this->currentCommandePosition = 0;
            $this->newPage();
        }

        $x = $this->layout->xBloc($this->currentCommandePosition);
        $y = $this->layout->yBloc($this->currentCommandePosition);

        new Commande($commande, $this->pdf, $x, $y, $this->layout);
    }

    protected function formeDecoupe($IDCommande, $fichier)
    {
        if ($this->currentCommandePosition < 3) {
            $this->currentCommandePosition++;
        } else {
            $this->currentCommandePosition = 0;
            $this->newPage();
        }

        $x = $this->layout->xBloc($this->currentCommandePosition);
        $y = $this->layout->yBloc($this->currentCommandePosition);

        new FormeDeDecoupe($IDCommande, $fichier, $this->pdf, $x, $y, $this->layout);
    }
} 