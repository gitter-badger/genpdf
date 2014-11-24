<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 12:02
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Position;

class Transporteurs
{
    const TRANSPORTEUR_EXPRESS = 'EXPRESS';

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $nbTransporteurs = $this->countTransporteurs();
        foreach ($nbTransporteurs as $label => $count) {
            $c = new Compteur();
            if ($label == 'DCH') {
                $c->labelFillColor = new FillColor(Color::cmyk(100, 100, 0, 0));
            }
            $c->count = $count;
            $c->label = $label;
            $c->draw($pdf, $position);
            $position = $position->add(new Position(10,0));
        }
    }




    protected function countTransporteurs()
    {
        $tab = [];
        foreach ($this->_p['commandes'] as $commande) {
            if (array_key_exists($commande['CodeTransporteur'], $tab)) {
                $tab[$commande['CodeTransporteur']] ++;
            } else {
                $tab[$commande['CodeTransporteur']] = 1;
            }
        }

        // on enlève le transporteur EXPRESS
        unset($tab[self::TRANSPORTEUR_EXPRESS]);

        return $tab;
    }
} 