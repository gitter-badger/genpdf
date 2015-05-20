<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:25
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\Negoce;

class Masterprint extends Negoce
{

    public function planche()
    {
        $planchePdf = new MasterprintPlanche(new \Exaprint\TCPDF\Dimensions(200, $this->layout->hBloc()),
            $this->pdf,
            $this->planche,
            new \Exaprint\TCPDF\Position(5, 12)
        );
    }

} 