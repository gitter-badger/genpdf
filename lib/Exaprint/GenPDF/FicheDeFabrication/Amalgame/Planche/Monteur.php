<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 12:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Monteur
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $date = New \DateTime($this->_p['DateAjout']);
        $text = 'Monteur : ' . $this->_p['PrenomMonteur'] . ' ' . $this->_p['NomMonteur'] . ' (' . $this->_p['EmailMonteur'] . ') - ' . $this->_p['ActiviteProduction'];
        $text .= "<br />" . 'Date : ' . $date->format('Y-m-d H:i:s') . ' - ' . $this->_p['NomAtelier'] . ' : ' . \Locale\Helper::formatMoney($this->_p['CoutPlanche']);

        $c         = new MultiCell();
        $c->isHtml = true;
        $c->text   = $text;
        $c->font   = new Font('bagc-reg', 10, new TextColor(Color::black()));
        $c->x      = $position->x;
        $c->y      = $position->y;
        $c->border = 1;
        $c->width  = 100;
        $c->height = 10;
        $c->draw($pdf);
    }
} 