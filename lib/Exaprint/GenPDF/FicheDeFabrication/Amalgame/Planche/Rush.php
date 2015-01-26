<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 11:06
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Rush
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        if ($this->_p['EstRush']) {

            $c           = new Cell();
            $c->position = $position;
            $c->text     = 'RUSH';
            $c->width    = 12;
            $c->height   = 6;
            $c->font     = new Font('bagc-bold', 16, new TextColor(Color::red()));
            $c->border = false;
            $c->draw($pdf, $position);

        }
    }
} 