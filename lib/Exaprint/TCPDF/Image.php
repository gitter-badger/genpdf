<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/02/2014
 * Time: 12:00
 */

namespace Exaprint\TCPDF;


class Image implements Element
{


    public $file;
    public $x = '';
    public $y = '';
    public $width = 0;
    public $height = 0;
    public $type = '';
    public $link = '';
    public $align = '';
    public $resize = false;
    public $dpi = 180;
    public $pAlign = '';
    public $isMask = false;
    public $imgMask = false;
    public $border = 0;
    public $fitbox = false;
    public $hidden = false;
    public $fitOnPage = false;
    public $alt = false;
    public $altImgs = [];

    public function draw(\TCPDF $pdf)
    {
        $pdf->Image(
            $this->file,
            $this->x,
            $this->y,
            $this->width,
            $this->height,
            $this->type,
            $this->link,
            $this->align,
            $this->resize,
            $this->dpi,
            $this->pAlign,
            $this->isMask,
            $this->imgMask,
            $this->border,
            $this->fitbox,
            $this->hidden,
            $this->fitOnPage,
            $this->alt,
            $this->altImgs
        );
    }

}