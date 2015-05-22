<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 16:33
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Masterprint;


use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Layout;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Details;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\CellHeightRatio;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\TextColor;

class MasterprintDetails extends Details
{

    function __construct($commande, $devis, \TCPDF $pdf, $x, $y, Layout $layout)
    {
        $this->pdf      = $pdf;
        $this->x        = $x;
        $this->y        = $y;
        $this->commande = $commande;
        $this->devis    = $devis;
        $this->layout   = $layout;

        $this->content();

        $this->footer();

        $this->border();
    }

    protected function content()
    {
        $html     = MasterprintDAL::cleanDetails($this->commande['CommentairePAO']);
        $fontSize = 14;
        if (count($html) >= 20) {
            $fontSize = 13;
        }
        if (count($html) >= 25) {
            $fontSize = 12;
        }
        if (count($html) >= 30) {
            $fontSize = 11;
        }
        if (count($html) >= 35) {
            $fontSize = 10;
        }

        $c                  = new MultiCell();
        $c->x               = $this->_x();
        $c->y               = $this->_y(5);
        $c->cellHeightRatio = new CellHeightRatio(0.9);
        $c->width           = $this->layout->wBloc();
        $c->height          = 5;
        $c->isHtml          = true;
        $c->font            = new Font('bagc-light', $fontSize);
        $c->text            = $html;
        $c->textColor       = new TextColor(Color::black());
        $c->draw($this->pdf);
    }

    protected function footer()
    {
        $q            = new MultiCell();
        $q->width     = $this->layout->wBloc();
        $q->font      = new Font('bagc-bold', 12);
        $q->fillColor = new FillColor(Color::cmyk(100, 0, 0, 0));
        $q->textColor = new TextColor(Color::white());
        $q->text      = "Pour plus de détails, référez-vous à votre devis Masterprint N°{$this->devis->NumeroDevisAtelier} {$this->devis->NomAtelier}";
        $q->x         = $this->_x();
        $q->y         = $this->_y($this->layout->hBloc() - 9);
        $q->border    = true;
        $q->height    = 9;
        $q->align     = Cell::ALIGN_LEFT;
        $q->valign    = Cell::VALIGN_CENTER;
        $q->fill      = true;
        $q->draw($this->pdf);
    }
}
