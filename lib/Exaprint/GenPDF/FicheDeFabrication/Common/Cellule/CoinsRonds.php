<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 04/04/2014
 * Time: 14:27
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Cellule;


use Exaprint\GenPDF\FicheDeFabrication\Common\ICellule;
use Exaprint\TCPDF\Cell;
use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\ImageInContainer;
use Exaprint\TCPDF\Position;
use Exaprint\TCPDF\Rect;
use Exaprint\TCPDF\RoundedRect;
use Exaprint\TCPDF\TextColor;

class CoinsRonds implements ICellule
{

    public function draw(Position $position, \TCPDF $pdf, $cellSize, array $commande)
    {
        if ($commande['DecoupeALaForme'] != null) {
            switch ($commande['DecoupeALaForme']) {
                case 522:
                    $cell            = new Cell();
                    $cell->position  = $position;
                    $cell->fill      = true;
                    $cell->fillColor = new FillColor(Color::black());
                    $cell->width     = $cellSize;
                    $cell->height    = $cellSize;
                    $cell->border    = true;
                    $cell->draw($pdf);

                    $rr             = new RoundedRect();
                    $rr->radius     = 2;
                    $rr->position   = $position->add(new Position(0.5, 0.5));
                    $rr->dimensions = new Dimensions($cellSize - 1, $cellSize - 1);
                    $rr->fillColor  = new FillColor(Color::white());
                    $rr->style      = Rect::STYLE_FILL;
                    $rr->draw($pdf);

                    $txt           = new Cell();
                    $txt->position = $position;
                    $txt->width    = $cellSize;
                    $txt->height   = $cellSize;
                    $txt->align    = Cell::ALIGN_CENTER;
                    $txt->vAlign   = Cell::VALIGN_CENTER;
                    $txt->font     = new Font('bagc-bold', 18, new TextColor(Color::black()));
                    $txt->fill     = false;
                    $txt->text     = t('ffa.cell.coins_ronds');
                    $txt->draw($pdf);
                    break;
                default:
                    Helper::drawEmptyCell($position, $pdf, $cellSize);
            }
        }
        if ($commande['DecoupeALaFormeNumerique'] || strpos($commande['CodeProduit'], 'CRN') !== false) {

            $file = '../assets/coins-ronds/CR_';
            $file .= !is_null($commande['HasDecoupeNumeriqueHG']) ? $commande['HasDecoupeNumeriqueHG'] : 0;
            $file .= !is_null($commande['HasDecoupeNumeriqueHD']) ? $commande['HasDecoupeNumeriqueHD'] : 0;
            $file .= !is_null($commande['HasDecoupeNumeriqueBD']) ? $commande['HasDecoupeNumeriqueBD'] : 0;
            $file .= !is_null($commande['HasDecoupeNumeriqueBG']) ? $commande['HasDecoupeNumeriqueBG'] : 0;
            $file .= '.png';

            $image = new ImageInContainer(
                $file,
                new Dimensions(71, 71),
                new Dimensions($cellSize, $cellSize),
                $position
            );

            $image->draw($pdf);
        }
    }

} 