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
        if ($planche['EncreAGratter']) {
            $this->finitions[] = $this->getEncreAGratter($planche);
        } else {
            $this->finitions[] = $this->getVernisUV($planche);
        }
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
        $finition->setLabel('PELL');
        $finition->setValue($this->getValuePelliculage($planche));
        if ($planche['EstSousTraitance']) {
            // fond blanc, texte noir
        } else {
            $finition->setValueFontColor(Color::white());
            $finition->setValueFillColor(Color::cmyk(0, 100, 80, 0));
        }
        return $finition;
    }

    protected function getVernis($planche)
    {
        $finition = new Finition();
        $finition->setLabel('VERN');
        $finition->setValue($this->getValueVernis($planche));
        return $finition;
    }

    protected function getVernisUV($planche)
    {
        $finition = new Finition();
        $finition->setLabel('UV');
        $finition->setValue($this->getValueVernisUV($planche));
        if ($planche['EstSousTraitance']) {
            // fond blanc, texte noir
        } else {
            $finition->setValueFontColor(Color::white());
            $finition->setValueFillColor(Color::cmyk(40, 80, 0, 0));
        }
        return $finition;
    }

    protected function getEncreAGratter($planche)
    {
        $finition = new Finition();
        $finition->setLabel('GRAT');
        $finition->setValue($this->getValueEncreAGratter($planche));
        $finition->setValueFontColor(Color::white());
        $finition->setValueFillColor(Color::cmyk(40, 80, 0, 0));
        return $finition;
    }

    protected function getValuePelliculage($planche)
    {
        if ($planche['EstSousTraitance']) {
            $planche['PelliculageRecto'] = $planche['PPelliculageRecto'];
            $planche['PelliculageVerso'] = $planche['PPelliculageVerso'];
        }

        if (is_null($planche['PelliculageRecto'])) return null;

        $txt = _('valeur_' . $planche['PelliculageRecto']) . ' R°';

        if ($planche['PelliculageVerso']) {
            $txt .= 'V°';
        }

        return $txt;
    }


    protected function getValueVernis($planche)
    {
        if ($planche['EstSousTraitance']) {
            $planche['VernisRecto'] = $planche['PVernisRecto'];
            $planche['VernisVerso'] = $planche['PVernisVerso'];
        }

        if (is_null($planche['VernisRecto'])) return null;

        $txt = _('valeur_' . $planche['VernisRecto']) . ' R°';

        if ($planche['VernisVerso']) {
            $txt .= 'V°';
        }

        return $txt;
    }


    protected function getValueVernisUV($planche)
    {
        if ($planche['EstSousTraitance']) {
            $planche['VernisSelectifRecto'] = $planche['PVernisSelectifRecto'];
            $planche['VernisSelectifVerso'] = $planche['PVernisSelectifVerso'];
        }

        if (is_null($planche['VernisSelectifRecto'])) return null;

        $txt = _('valeur_' . $planche['VernisSelectifRecto']) . ' R°';

        if ($planche['VernisSelectifVerso']) {
            $txt .= 'V°';
        }

        if ($planche['NomAtelierSousTraitance'] && in_array(22, $planche['ActionsSousTraitance'])) {
            $txt .= ' ' . $planche['NomAtelierSousTraitance'];
        }

        return $txt;
    }


    protected function getValueEncreAGratter($planche)
    {
        if ($planche['EstSousTraitance']) {
            $planche['EncreAGratter'] = $planche['PEncreAGratter'];
        }

        if (is_null($planche['EncreAGratter'])) return null;

        $txt = _('valeur_' . $planche['EncreAGratter']) . ' R°';

        return $txt;
    }
} 