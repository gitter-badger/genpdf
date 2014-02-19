<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class Amalgame
{

    public static $marge = 5;
    public static $hSouche = 8;
    public static $width = 200;
    public static $height = 284;

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

    public function __construct($planche)
    {
        $this->logger = new Logger('ff', [new RotatingFileHandler('/var/tmp/fiche-de-fab.log')]);

        $this->planche = $planche;

        $this->pdf = new \TCPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );

        $this->pdf->SetFont('bagc-reg');
        $this->pdf->SetMargins(0, 0, 0, true);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetAutoPageBreak(false);
        $this->newPage();

        $planchePdf = new Planche($this->pdf, $planche);
        $planchePdf->draw();

        foreach ($this->planche['commandes'] as $commande) {
            $this->commande($commande);
        }
    }

    protected function newPage()
    {
        $this->pdf->AddPage();
        $this->souche();

    }

    protected function souche()
    {
        $this->pdf->Rect(self::$marge, 0, 200, self::$hSouche);
    }

    protected function commande($commande)
    {
        if ($this->currentCommandePosition < count(self::$commandePositions) - 1) {
            $this->currentCommandePosition++;
        } else {
            $this->currentCommandePosition = 0;
            $this->newPage();
        }

        $x = self::$commandePositions[$this->currentCommandePosition]['x'];
        $y = self::$commandePositions[$this->currentCommandePosition]['y'];

        $commande          = new Commande($commande, $this->pdf, $x, $y);
        $this->commandes[] = $commande;
    }
} 