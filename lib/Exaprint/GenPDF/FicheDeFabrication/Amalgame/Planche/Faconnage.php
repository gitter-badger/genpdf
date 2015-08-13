<?php

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;

use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Faconnage
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;

        /**
         * Cas à part :
         * - prédécoupe
         * - découpe numérique
         */

        $this->_p['AvecPredecoupe']       = false;
        $this->_p['AvecDecoupeNumerique'] = false;

        foreach ($planche['commandes'] as $commande) {

            if ($commande['Predecoupe']) {
                $this->_p['AvecPredecoupe'] = true;
            }

            if ($commande['DecoupeALaFormeNumerique']) {
                $this->_p['AvecDecoupeNumerique'] = true;
            }

            if (strpos($commande['CodeProduit'], 'CRN') !== false) {
                $this->_p['AvecDecoupeNumerique'] = true;
            }
        }
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $c            = new MultiCell();
        $c->text      = $this->getMessage($this->_p);
        $c->font      = new Font('bagc-reg', 13, new TextColor(Color::white()));
        $c->fill      = true;
        $c->fillColor = new FillColor(($c->text) ? Color::cmyk(100, 0, 100, 0) : Color::white());
        $c->width     = 100;
        $c->height    = 16.5;
        $c->x         = $position->x;
        $c->y         = $position->y;
        $c->border    = 1;
        $c->draw($pdf);
    }

    public function getMessage($planche)
    {
        if ($planche['EstSousTraitance'] == 1) {
            return "";
        }

        $message = [];
        if ($planche['AvecRainage']) $message[] = t('ffa.planche.faco.avec_rainage');
        if ($planche['AvecPliage']) $message[] = t('ffa.planche.faco.avec_pliage');
        if ($planche['AvecDecoupeOutil']) $message[] = t('ffa.planche.faco.avec_decoupe_outil');

        if ($planche['AvecDecoupe']) $message[] = t('ffa.planche.faco.avec_decoupe');
        if ($planche['AvecPredecoupe']) $message[] = t('ffa.planche.faco.avec_predecoupe');
        if ($planche['AvecDecoupeNumerique']) $message[] = t('ffa.planche.faco.avec_decoupe_numerique');

        if ($planche['AvecDecorticage']) $message[] = t('ffa.planche.faco.avec_decorticage');
        if ($planche['AvecPerforation']) $message[] = t('ffa.planche.faco.avec_perfo');

        return implode(' - ', $message);

    }
} 