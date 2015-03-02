<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/04/2014
 * Time: 15:46
 */

namespace Exaprint\GenPDF\FicheDeFabrication\Common;


use Exaprint\TCPDF\Color;
use Exaprint\TCPDF\LineStyle;

class Stylesheet {

    protected static $_lineStyle;

    public static function lineStyle()
    {
        if(!isset(self::$_lineStyle)){
            self::$_lineStyle = new LineStyle();
            self::$_lineStyle->width = 0.05;
            self::$_lineStyle->color = Color::black();
        }
        return self::$_lineStyle;
    }

    public static function red()
    {
        return Color::cmyk(0, 100, 100, 0);
    }

} 