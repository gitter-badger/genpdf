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

class Observations
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $c = new MultiCell();
        $c->text = $this->_p['ObservationsTechnique'];
        $c->font = new Font('bagc-reg', 12, new TextColor(Color::black()));
        $c->x = $position->x;
        $c->y = $position->y;
        $c->border = 1;
        $c->width = 100;
        $c->height = 40;
        $c->draw($pdf);
    }
} 