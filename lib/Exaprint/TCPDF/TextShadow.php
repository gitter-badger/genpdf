<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24/02/2014
 * Time: 09:15
 */

namespace Exaprint\TCPDF;


class TextShadow implements DrawingContext
{

    const BLEND_MODE_NORMAL     = 'Normal';
    const BLEND_MODE_MULTIPLY   = 'Multiply';
    const BLEND_MODE_SCREEN     = 'Screen';
    const BLEND_MODE_OVERLAY    = 'Overlay';
    const BLEND_MODE_DARKEN     = 'Darken';
    const BLEND_MODE_LIGHTEN    = 'Lighten';
    const BLEND_MODE_COLORDODGE = 'ColorDodge';
    const BLEND_MODE_COLORBURN  = 'ColorBurn';
    const BLEND_MODE_HARDLIGHT  = 'HardLight';
    const BLEND_MODE_SOFTLIGHT  = 'SoftLight';
    const BLEND_MODE_DIFFERENCE = 'SoftLight';
    const BLEND_MODE_EXCLUSION  = 'Exclusion';
    const BLEND_MODE_HUE        = 'Hue';
    const BLEND_MODE_SATURATION = 'Saturation';
    const BLEND_MODE_COLOR      = 'Color';
    const BLEND_MODE_LUMINOSITY = 'Luminosity.';

    public $depthW = 0;
    public $depthH = 0;
    public $color = false;
    public $opacity = 1;
    public $blendMode = self::BLEND_MODE_NORMAL;
    protected $previous = [];


    public function apply(\TCPDF $pdf)
    {
        $this->previous = $pdf->getTextShadow();

        $pdf->setTextShadow([
            'enabled'    => true,
            'depth_w'    => $this->depthW,
            'depth_h'    => $this->depthH,
            'color'      => $this->color,
            'opacity'    => $this->opacity,
            'blend_mode' => $this->blendMode,
        ]);
    }

    public function revert(\TCPDF $pdf)
    {

        $pdf->setTextShadow($this->previous);
    }


} 