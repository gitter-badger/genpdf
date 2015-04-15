<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Common\ICellule;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\ImageInContainer;
use Exaprint\TCPDF\Position;

class Encollage implements ICellule
{

    public $map = [
        '470' => '../assets/encollage/en-tete.png',
        '471' => '../assets/encollage/en-pied.png',
        '472' => '../assets/encollage/a-droite.png',
        '473' => '../assets/encollage/a-gauche.png',
        '621' => '../assets/encollage/petit-cote.png',
        '771' => '../assets/encollage/grand-cote.png',
    ];

    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if (array_key_exists($commande['Encollage'], $this->map)) {
            $file  = $this->map[$commande['Encollage']];
            $image = new ImageInContainer(
                $file,
                new Dimensions(71, 71),
                new Dimensions($cellSize, $cellSize),
                $position
            );

            $image->draw($pdf);
        } else {
            Helper::drawEmptyCell($position, $pdf, $cellSize);
        }
    }
} 