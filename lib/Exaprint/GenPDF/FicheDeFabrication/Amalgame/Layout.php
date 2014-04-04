<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/02/2014
 * Time: 14:56
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Amalgame;


class Layout
{

    public $pageWidth = 210;
    public $pageHeight = 297;
    public $soucheHeight = 12;
    public $marge = 5;
    public $pied = 12;
    public $gouttiere = 4;
    /*
     * PLANCHE pXXX
     */
    public $pEnteteHeight = 16;
    public $pEnteteIdPlancheWidth = 40;
    public $pEnteteExpeSansFaconnageWidth = 30;
    public $pEnteteExpeAvecFaconnageWidth = 30;
    public $pEnteteImperatifsWidth = 20;
    public $pEnteteNbCommandesWidth = 20;
    public $pRangHeight = 10;
    public $pRangGroupeWidth = 30;
    public $pCodeBarreWidth = 30;
    public $pCodeBarreMargin = 2;
    public $pObservationsWidth = 100;
    public $pObservationsHeight = 20;
    public $pRangs = [
        "support",
        "impression",
        "pelliculage",
        "vernis",
        "vernisSelectif",
        "faconnage"
    ];

    /*
     * COMMANDE c
     */
    public $cEnteteIdsWidth = 32;
    public $cEnteteHeight = 10;
    public $cEnteteQuantiteWidth = 28;

    public $cVisuelsHeight = 65;
    public $cGrilleColCount = 3;
    public $cCodeBarreWidth = 36;
    public $cCodeBarreHeight = 16;
    public $cCodeBarreMargin = 2;
    public $cCodeProduitHeight = 8;
    public $cLivraisonCodePostalWidth = 19;
    public $cLivraisonCodePostalHeight = 9;

    public $cLivraisonCodePaysWidth = 9;
    public $cLivraisonCodePaysHeight = 9;

    public $cExpeditionDateWidth = 16;
    public $cExpeditionDateHeight = 10;
    public $cExpeditionDateFontSize = 20;

    public $cIdCommandeHeight = 7;

    public $cGrilleCellules = [
        ['BatPapier', 'Justificatif', 'Retirage'],
        ['SousTraitance', 'Groupage', ''],
        ['', '', ''],
        ['Predecoupe', '', ''],
        ['Pliage', 'Rainage', 'Perforation'],
        ['', 'PorteCarte', 'Societe']
    ];


    public function hGrille()
    {
        return $this->hBloc() - $this->cEnteteHeight - $this->cVisuelsHeight;
    }

    public function grilleColCount()
    {
        return count($this->cGrilleCellules[0]);
    }

    public function grilleRowCount()
    {
        return count($this->cGrilleCellules);
    }

    public function cellule()
    {
        return $this->hGrille() / $this->grilleRowCount();
    }

    public function wCommandeFormat()
    {
        return $this->wBloc() - $this->cEnteteIdsWidth - $this->cEnteteQuantiteWidth;
    }

    public function xBloc($position)
    {
        if ($position % 2 == 0)
            return $this->marge;

        return $this->marge + $this->wBloc() + $this->gouttiere;
    }

    public function yBloc($position)
    {
        if ($position < 2)
            return $this->soucheHeight;

        return $this->soucheHeight + $this->hBloc() + $this->gouttiere;
    }

    public function wRang()
    {
        return $this->pageWidth - ($this->marge * 2);
    }

    public function hRang()
    {
        return $this->hBloc();
    }

    public function wBloc()
    {
        return ($this->pageWidth - $this->marge * 2 - $this->gouttiere) / 2;
    }

    public function hBloc()
    {
        return ($this->pageHeight - $this->soucheHeight - $this->pied - $this->gouttiere) / 2;
    }

} 