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

        foreach ($planche['commandes'] as $commande) {
            if ($commande['Pliage']) {
                $this->_p['AvecPliage'] = true;
            }

            if ($commande['Rainage']) {
                $this->_p['AvecRainage'] = true;
            }

            if ($commande['Perforation']) {
                $this->_p['AvecPerforation'] = true;
            }

            if ($commande['Decoupe']) {
                $this->_p['AvecDecoupe'] = true;
            }

            //if ($commande['DecoupeALaFormeNumerique']) {
            //    $this->_p['AvecDecoupeNumerique'] = true;
            //}
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
        $message = [];
        if ($planche['AvecRainage']) $message[] = 'Rainage';
        if ($planche['AvecPliage']) $message[] = 'Pliage';
        if ($planche['AvecPerforation']) $message[] = 'Perforation';
        if ($planche['AvecDecoupe']) $message[] = 'Découpe';
        //if ($planche['AvecDecoupeNumerique']) $message[] = 'Découpe numérique';

        if ($planche['Decoupe']) $message[] = _('valeur_' . $planche['Decoupe']);
        if ($planche['Rainage']) $message[] = _('valeur_' . $planche['Rainage']);
        if ($planche['Predecoupe']) $message[] = _('valeur_' . $planche['Predecoupe']);
        if ($planche['DecoupeALaForme']) $message[] = _('valeur_' . $planche['DecoupeALaForme']);

        return implode(' - ', $message);

    }
} 