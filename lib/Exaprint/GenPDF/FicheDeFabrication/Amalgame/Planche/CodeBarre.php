<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 17:03
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\LineStyle;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Text;

class CodeBarre extends Cellule
{
    public $id;

    public function __construct($IDPlanche)
    {
        parent::__construct();
        $this->id = $IDPlanche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $margin = 4;
        $pdf->Rect($position->x, $position->y, $this->dimensions->width, $this->dimensions->height, 's');
        $pdf->write1DBarcode(
            $this->id,
            'C39',
            $position->x + $margin / 2,
            $position->y + $margin / 2,
            $this->dimensions->width - $margin,
            $this->dimensions->height - $margin,
            0.4,
            '',
            'N'
        );
    }

} 