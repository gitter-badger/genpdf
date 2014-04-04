<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Layout;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
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
    protected $commandes = [];
    protected $currentCommandePosition = 1;
    protected $layout;
    protected $pageNumber = 0;
    protected $_pageCount;

    public function __construct($planche)
    {

        $this->planche = $planche;

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

        $planchePdf = new Planche($this->pdf, $planche, $this->layout);
        $planchePdf->draw();

        foreach ($this->planche['commandes'] as $commande) {
            $this->commande($commande);
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
        if(!isset($this->_pageCount))
            $this->_pageCount = ceil((count($this->planche['commandes']) + 2) / 4);

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

        $commande          = new Commande($commande, $this->pdf, $x, $y, $this->layout);
        $this->commandes[] = $commande;
    }
} 