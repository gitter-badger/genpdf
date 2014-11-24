<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 09:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\MultiCell;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\TextColor;

class DetailSousTraitance
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $c            = new MultiCell();
        $c->text      = $this->getMessage($this->_p);
        $c->font      = new Font('bagc-reg', 13, new TextColor(Color::white()));
        $c->fill      = true;
        $c->fillColor = new FillColor(($c->text) ? Color::cmyk(0, 75, 100, 0) : Color::white());
        $c->width     = 100;
        $c->height    = 16.5;
        $c->x         = $position->x;
        $c->y         = $position->y;
        $c->border    = 1;
        $c->draw($pdf);
    }

    public function getMessage()
    {
        $message = '';

        foreach ($this->_p['ActionsSousTraitance'] as $action) {

            if (empty($action['NomAtelierSousTraitance'])) {
                continue;
            }

            // on affiche ce bloc si on n'est pas sur une fiche de sous-traitance
            if ($this->_p['EstSousTraitance'] == 0) {
                $message .= $action['NomAtelierSousTraitance'];
                $message .= ' (' . $action['IDPlancheSousTraitance'] . ') : ';
            }

            $actions = [];
            foreach ($action['actions'] as $a) {
                $actions[] = _('action_planche_' . $a);
            }

            $message .= implode(' - ', $actions);
            $message .= "\n";
        }

        return $message;
    }
} 