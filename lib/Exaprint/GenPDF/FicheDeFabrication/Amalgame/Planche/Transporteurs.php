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
    const TRANSPORTEUR_EXPRESS   = 'EXPRESS';
    const TRANSPORTEUR_CORREOS   = 'CORREOS';
    const TRANSPORTEUR_INTERLINK = 'INTERLINK';
    const TRANSPORTEUR_BARTOLINI = 'BARTOLINI';
    const TRANSPORTEUR_FEDEX     = 'FEDEX';
    const TRANSPORTEUR_MORY      = 'Mory';
    const TRANSPORTEUR_DACHSER   = 'DCH';

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
            $c->label = self::getTransporteurLabel($label);
            $c->draw($pdf, $position);
            $position = $position->add(new Position(10, 0));
        }
    }

    protected function countTransporteurs()
    {
        $tab = [];
        foreach ($this->_p['commandes'] as $commande) {
            if (array_key_exists($commande['CodeTransporteur'], $tab)) {
                $tab[$commande['CodeTransporteur']]++;
            } else {
                $tab[$commande['CodeTransporteur']] = 1;
            }
        }

        // on enlève le transporteur EXPRESS
        unset($tab[self::TRANSPORTEUR_EXPRESS]);

        return $tab;
    }

    public static function getTransporteurLabel($label)
    {
        switch ($label) {
            case self::TRANSPORTEUR_EXPRESS:
                $ret = 'EXP';
                break;
            case self::TRANSPORTEUR_CORREOS:
                $ret = 'COR';
                break;
            case self::TRANSPORTEUR_INTERLINK:
                $ret = 'ILK';
                break;
            case self::TRANSPORTEUR_BARTOLINI:
                $ret = 'BRT';
                break;
            case self::TRANSPORTEUR_FEDEX:
                $ret = 'FED';
                break;
            case self::TRANSPORTEUR_MORY:
                $ret = 'MOR';
                break;
            case self::TRANSPORTEUR_DACHSER:
                $ret = 'DAC';
                break;
            default:
                $ret = $label;
                break;
        }
        return $ret;
    }
} 