<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 21:11
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Position;

class Finitions
{

    /** @var  Finition[] */
    public $finitions = [];

    public function __construct($planche)
    {
        $this->finitions[] = $this->getPelliculage($planche);
        $this->finitions[] = $this->getVernis($planche);
        $this->finitions[] = $this->getVernisUV($planche);
    }

    public function draw(\TCPDF $pdf, Position $position)
    {

        foreach ($this->finitions as $finition) {
            $finition->draw($pdf, $position);
            $position = $position->add(new Position(0, 11));
        }
    }

    protected function getPelliculage($planche)
    {
        $finition = new Finition();
        $finition->setLabel(t('ffa.planche.finitions.pelliculage'));
        $finition->setValue($this->getValuePelliculage($planche));
        $finition->setValueFontColor(Color::white());
        $finition->setValueFillColor(Color::cmyk(0, 100, 80, 0));
        return $finition;
    }

    protected function getVernis($planche)
    {
        $finition = new Finition();
        $finition->setLabel(t('ffa.planche.finitions.vernis'));
        $finition->setValue($this->getValueVernis($planche));
        return $finition;
    }

    protected function getVernisUV($planche)
    {
        $finition = new Finition();
        $finition->setLabel(t('ffa.planche.finitions.vernis_uv'));
        $finition->setValue($this->getValueVernisUV($planche));
        $finition->setValueFontColor(Color::white());
        $finition->setValueFillColor(Color::cmyk(40, 80, 0, 0));
        return $finition;
    }

    protected function getValuePelliculage($planche)
    {
        if (is_null($planche['PelliculageRecto'])) return null;

        $txt = t('valeur_' . $planche['PelliculageRecto']) . ' ' . t('ffa.abbr.recto');

        if ($planche['PelliculageVerso']) {
            $txt .= t('ffa.abbr.verso');
        }

        return $txt;
    }


    protected function getValueVernis($planche)
    {
        if (is_null($planche['VernisRecto'])) return null;

        $txt = t('valeur_' . $planche['VernisRecto']) . ' ' . t('ffa.abbr.recto');

        if ($planche['VernisVerso']) {
            $txt .= t('ffa.abbr.verso');
        }

        return $txt;
    }


    protected function getValueVernisUV($planche)
    {
        if (is_null($planche['VernisSelectifRecto'])) return null;

        $txt = t('valeur_' . $planche['VernisSelectifRecto']) . ' ' . t('ffa.abbr.recto');

        if ($planche['VernisSelectifVerso']) {
            $txt .= t('ffa.abbr.verso');
        }

        if ($planche['NomAtelierSousTraitance'] && in_array(22, $planche['ActionsSousTraitance'])) {
            $txt .= ' ' . $planche['NomAtelierSousTraitance'];
        }

        return $txt;
    }
} 