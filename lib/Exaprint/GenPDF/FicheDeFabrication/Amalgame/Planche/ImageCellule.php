<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 20:17
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Image;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Rect;

class ImageCellule extends Cellule{

    public $src;


    public function draw(\TCPDF $pdf, Position $position)
    {
        $d = $this->dimensions;

        $rect             = new Rect();
        $rect->dimensions = $d;
        $rect->position   = $position;
        $rect->style      = Rect::STYLE_STROKE;
        $rect->draw($pdf);

        $image         = new Image();
        $image->height = $d->height - 2;
        $image->width  = $d->width - 2;
        $image->file   = $this->src;
        $image->x      = $position->x + 1;
        $image->y      = $position->y + 1;
        $image->draw($pdf);
    }
} 