<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:48
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Negoce;


use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceImpression;
use Exaprint\GenPDF\FicheDeFabrication\Negoce\Planche\NegoceFinitions;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\PEFC;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Compteurs;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\DetailSousTraitance;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Faconnage;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Identification;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\IndicationsCommandes;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Observations;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Rush;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Transporteurs;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche\Monteur;
use Exaprint\GenPDF\FicheDeFabrication\Amalgame\Planche;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\Position;

class NegocePlanche extends Planche
{
    /** @var  array */
    public $planche;
    /** @var  Position */
    public $position;
    /** @var  \TCPDF */
    public $pdf;
    /** @var  Dimensions */
    public $dimensions;

    function __construct($dimensions, $pdf, $planche, $position)
    {
        $this->dimensions       = $dimensions;
        $this->pdf              = $pdf;
        $this->planche          = $planche;
        $this->position         = $position;
        $this->hauteurFinitions = 33;

        $this->identification();
        $this->impression();
        $this->finitions();
        $this->faconnage();
        $this->detailSousTraitance();
        $this->rush();
        $this->indicationsCommandes();
        $this->compteurs();
        $this->observations();
        $this->pefc();
        $this->transporteurs();
        $this->monteur();
    }


    public function identification()
    {
        $identification = new Identification($this->planche);
        $identification->draw($this->pdf, $this->position->add(new Position(0, 0)));
    }

    public function impression()
    {
        $impression = new NegoceImpression($this->planche);
        $impression->draw($this->pdf, $this->position->add(new Position(0, 17)));

    }

    public function finitions()
    {
        $finitions = new NegoceFinitions($this->planche);
        $finitions->draw($this->pdf, $this->position->add(new Position(0, 33)));
        $this->hauteurFinitions = 33 + 11 * $finitions->nbLines;
    }

    public function faconnage()
    {
        $faconnage = new Faconnage($this->planche);
        $faconnage->draw($this->pdf, $this->position->add(new Position(100, 33)));
    }

    public function detailSousTraitance()
    {
        $detailST = new DetailSousTraitance($this->planche);
        $detailST->draw($this->pdf, $this->position->add(new Position(100, 49.5)));
    }

    public function rush()
    {
        $indications = new Rush($this->planche);
        $indications->draw($this->pdf, $this->position->add(new Position(87, $this->hauteurFinitions)));
    }

    public function indicationsCommandes()
    {
        $indications = new IndicationsCommandes($this->planche);
        $indications->draw($this->pdf, $this->position->add(new Position(0, $this->hauteurFinitions)));
    }

    public function observations()
    {
        $observations = new Observations($this->planche);
        $observations->draw($this->pdf, $this->position->add(new Position(100, 66)));
    }

    public function pefc()
    {
        if ($this->planche['estPEFC']) {
            $compteurs = new PEFC($this->planche);
            $compteurs->draw($this->pdf, $this->position->add(new Position(82, 118)));
        }
    }

    public function compteurs()
    {
        $compteurs = new Compteurs($this->planche);
        $compteurs->draw($this->pdf, $this->position->add(new Position(0, 120)));
    }

    public function transporteurs()
    {
        $compteurs = new Transporteurs($this->planche);
        $compteurs->draw($this->pdf, $this->position->add(new Position(100, 120)));
    }

    public function monteur()
    {
        $monteur = new NegoceMonteur($this->planche);
        $monteur->draw($this->pdf, $this->position->add(new Position(100, 96)));
    }

} 