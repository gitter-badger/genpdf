<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce;


use Exaprint\GenPDF\FicheDeFabrication\Common\Cellule\PEFC;
use Exaprint\GenPDF\FicheDeFabrication\Common\Common;
use Exaprint\GenPDF\FicheDeFabrication\Common\Layout;
use Exaprint\GenPDF\FicheDeFabrication\Common\Planche;

class Negoce extends Common
{

    const PRODUIT_AMALGAME = 'AMAL';

    public function __construct($planche)
    {

        $this->planche = $planche;

        $nbCommandes = count($this->planche['commandes']);

        $this->planche['estPEFC'] = false;
        $this->planche['EstRush'] = false;
        $this->planche['contientAmalgame'] = false;
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
            if ($c['EstRush']) {
                $this->planche['EstRush'] = true;
            }
            if (strpos($c['CodeProduit'], self::PRODUIT_AMALGAME) !== false) {
                $this->planche['contientAmalgame'] = true;
            }
            if ($c['EstModeleCouleur']) {
                $c['BAT'] = true;
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

        $planchePdf = new NegocePlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
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
} 