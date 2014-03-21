<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 21:32
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\ICellule;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\ImageInContainer;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class Societe implements ICellule
{
    public $map = [
        1 => '../assets/flags/FR.png',
        2 => '../assets/flags/ES.png',
        8 => '../assets/flags/IT.png',
        9 => '../assets/flags/GB.png',
    ];

    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        $file  = $this->map[$commande['IDSociete']];
        $image = new ImageInContainer(
            $file,
            new Dimensions(96, 64),
            new Dimensions($cellSize, $cellSize),
            $position
        );

        $image->draw($pdf);
    }
} 