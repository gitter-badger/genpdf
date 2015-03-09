<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche;


use Exaprint\GenPDF\FicheDeFabrication\Common\Planche\Finition;
use Exaprint\TCPDF\Position;

class NegoceFinitions
{

    /** @var  Finition[] */
    public $finitions = [];

    public function __construct($planche)
    {
        $lines = 0;

        $finition = new Finition1();
        $finition->setTitle('COUV');
        $finition->setA1('350g Mat Condat Périgord');
        $finition->setA2('4+0');
        $finition->setA3('Bril. R°');
        $this->finitions[] = $finition;
        $lines++;

        $finition = new Finition2();
        $finition->setTitle('+');
        $finition->setA1('Vernis UV sélectif : Vernis UV sélectif 3D');
        $finition->setA2('R');
        $this->finitions[] = $finition;
        $lines++;

        $options = ['Numérotation', 'Découpe', 'Test'];
        for ($i = 0; $i < count($options); $i += 2) {
            $finition = new Finition3();
            $finition->setOption1('+');
            $finition->setA1($options[$i]);
            if ($i + 1 < count($options)) {
                $finition->setOption2('+');
                $finition->setA2($options[$i + 1]);
            }
            $this->finitions[] = $finition;
            $lines++;
        }

        $this->nbLines = $lines;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {

        foreach ($this->finitions as $finition) {
            $finition->draw($pdf, $position);
            $position = $position->add(new Position(0, 11));
        }
    }
} 