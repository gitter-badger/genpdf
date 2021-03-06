<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Commande;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Layout;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\TextColor;

class Details extends Commande
{

    const TRANSPORTEUR_EXPRESS = 'EXPRESS';

    /**
     * @var \TCPDF
     */
    protected $pdf;

    public $x;

    public $y;

    protected $commande;

    protected $layout;

    function __construct($commande, \TCPDF $pdf, $x, $y, Layout $layout)
    {
        $this->pdf      = $pdf;
        $this->x        = $x;
        $this->y        = $y;
        $this->commande = $commande;
        $this->layout   = $layout;

        $this->content();

        $this->border();
    }

    protected function _x($x = 0)
    {
        return $this->x + $x;
    }

    protected function _y($y = 0)
    {
        return $this->y + $y;
    }

    protected function border()
    {
        $this->pdf->Rect(
            $this->_x(),
            $this->_y(),
            $this->layout->wBloc(),
            $this->layout->hBloc()
        );
    }

    protected function content()
    {
        $html = NegoceDAL::displayDetails($this->commande);
        $fontSize = 14;
        if (count($html) >= 20) {$fontSize = 13;}
        if (count($html) >= 25) {$fontSize = 12;}
        if (count($html) >= 30) {$fontSize = 11;}
        if (count($html) >= 35) {$fontSize = 10;}

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y(5);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc();
        $c->isHtml          = true;
        $c->font = new Font('bagc-light', $fontSize);
        $c->text      = implode('<br />', $html);
        $c->textColor = new TextColor(Color::black());
        $c->draw($this->pdf);
    }
}
