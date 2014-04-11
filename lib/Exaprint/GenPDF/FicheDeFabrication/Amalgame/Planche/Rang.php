<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:23
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\Position;

class Rang
{

    /** @var Cellule[] */
    public $cellules = [];

    /** @var Dimensions */
    public $dimensions;

    public function draw(\TCPDF $pdf, Position $position)
    {
        foreach ($this->cellules as $cellule) {
            if (!$cellule->dimensions->height) {
                $cellule->dimensions->height = $this->dimensions->height;
            }
            $cellule->draw($pdf, $position);
            $position = $position->add(new Position($cellule->dimensions->width, 0));
        }
    }
} 