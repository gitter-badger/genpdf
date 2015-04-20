<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 12:02
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Position;

class Compteurs
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $c = new Compteur();
        $c->count = $this->countPortesCartes();
        $c->label = t('ffa.planche.compteurs.porte_cartes');
        $c->draw($pdf, $position);
        $position = $position->add(new Position(10,0));


        $c = new Compteur();
        $c->count = $this->countJustificatifs();
        $c->label = t('ffa.planche.compteurs.justificatifs');
        $c->draw($pdf, $position);
        $position = $position->add(new Position(10,0));


        $c = new Compteur();
        $c->count = $this->countKitFidelite();
        $c->label = t('ffa.planche.compteurs.kit_fidelite');
        $c->draw($pdf, $position);
    }




    protected function countPortesCartes()
    {
        $nbPortesCarte = 0;
        foreach ($this->_p['commandes'] as $commande) {
            $nbPortesCarte += $commande['NbPorteCarte'];
        }
        return $nbPortesCarte;
    }

    protected function countJustificatifs()
    {
        $justificatifs = 0;
        foreach ($this->_p['commandes'] as $commande) {
            if ($commande['Justificatif']) {
                $justificatifs++;
            }
        }
        return $justificatifs;
    }

    protected function countKitFidelite()
    {
        $kf = 0;
        foreach ($this->_p['commandes'] as $commande) {
            if (strpos($commande['CodeProduit'], 'CCOMF') === 0 || strpos($commande['CodeProduit'], 'CCOMDF')) {
                $kf++;
            }
        }
        return $kf;
    }
} 