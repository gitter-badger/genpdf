<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/04/2014
 * Time: 11:06
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\Position;

class IndicationsCommandes
{

    protected $_p;

    public function __construct($planche)
    {
        $this->_p = $planche;
    }

    public function draw(\TCPDF $pdf, Position $position)
    {
        $rows = $this->process();
        $labelWidth = 22;
        $x = $labelWidth;
        $h = 10;
        foreach($rows as $label => $pages){
            $c = new Cellule();
            $c->value = $label;
            $c->dimensions = new Dimensions($labelWidth, $h);
            $c->valueFont->size = 14;
            $c->valueFont->family = 'bagc-medium';
            $c->vAlign = Cell::VALIGN_CENTER;
            $c->draw($pdf, $position);
            foreach($pages as $numero => $positions){
                $page = new Page();

                $page->numero = $numero;
                foreach($positions as $index => $detail){
                    $page->setActive($index);
                }
                $page->draw($pdf, $position->add(new Position($x, 0)));
                $x += 6;
            }
            $x = $labelWidth;
            $position = $position->add(new Position(0, $h));
        }
    }

    public function process()
    {
        $result = [];

        foreach ($this->_p['commandes'] as $i => $commande) {
            $page     = (int)ceil(($i + 3) / 4);
            $position = (int)(($i + 2) % 4);

            if (trim($commande['CommentairePAO']) != '') {
                if (!isset($result['Com. PAO'][$page])) $result['Com. PAO'][$page] = [];
                $result['Com. PAO'][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }

            $tests = [
                'BAT'                  => t('ffa.indications_commandes.bat'),
                'EstImperatif'         => t('ffa.indications_commandes.imperatif'),
                'IDCommandePrincipale' => t('ffa.indications_commandes.retirage'),
            ];

            foreach ($tests as $col => $label) {
                if ($commande[$col]) {
                    if (!isset($result[$label])) $result[$label] = [];
                    if (!isset($result[$label][$page])) $result[$label][$page] = [];
                    $result[$label][$page][$position] = ['IDCommande' => $commande['IDCommande']];
                }
            }

            $k = 'Façonnage';
            if ($commande['Pliage'] ||
                $commande['Rainage'] ||
                $commande['Perforation'] ||
                $commande['Predecoupe'] ||
                $commande['DecoupeALaForme']) {
                if (!isset($result[$k])) $result[$k] = [];
                if (!isset($result[$k][$page])) $result[$k][$page] = [];
                $result[$k][$page][$position] = ['IDCommande' => $commande['IDCommande']];
            }

        }

        return $result;

    }
} 