<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 12:02
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Image;
use Exaprint\TCPDF\Position;

class PEFC
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $image         = new Image();
        $image->height = 18 - 2;
        $image->width  = 16 - 2;
        $image->file   = '../assets/pefc.png';
        $image->x      = $position->x + 1;
        $image->y      = $position->y + 1;
        $image->draw($pdf);
    }
} 