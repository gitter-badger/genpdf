<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 16:00
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common\Planche;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\Dimensions;
use Exaprint\TCPDF\FillColor;
use Exaprint\TCPDF\Font;
use Exaprint\TCPDF\TextColor;

class Finition extends Rang
{

    protected $_label;

    protected $_value;

    public function __construct()
    {
        $this->applyDefaults();
        $label             = new Cellule();
        $label->dimensions = new Dimensions(22, 11);
        $label->fillColor  = new FillColor(Color::greyscale(80));
        $label->valueFont  = new Font('bagc-bold', 28, new TextColor(Color::white()));

        $value             = new Cellule();
        $value->dimensions = new Dimensions(78, 11);
        $value->fillColor  = new FillColor(Color::white());
        $value->valueFont  = new Font('bagc-light', 30, new TextColor(Color::black()));

        $this->_label = $label;
        $this->_value = $value;

        $this->cellules = [&$this->_label, &$this->_value];

    }

    protected function applyDefaults()
    {
        $this->_valueFillColor = Color::white();
        $this->_valueFontColor = Color::black();
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->_value->value = $value;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->_label->value = $label;
    }

    public function setValueFillColor($color)
    {
        $this->_value->fillColor->color = $color;
    }
    public function setValueFontColor($color)
    {
        $this->_value->valueFont->textColor->color = $color;
    }


} 