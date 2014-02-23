<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 21:18
 */

namespace Exaprint\TCPDF;


class Color
{
    public $col1 = -1;
    public $col2 = -1;
    public $col3 = -1;
    public $col4 = -1;
    public $ret = false;
    public $name = '';

    public static function greyscale($value)
    {
        $color       = new self();
        $color->col1 = $value;
        return $color;
    }

    /**
     * @param $c
     * @param $m
     * @param $y
     * @param $k
     * @return Color
     */
    public static function cmyk($c, $m, $y, $k)
    {
        $color       = new self();
        $color->col1 = $c;
        $color->col2 = $m;
        $color->col3 = $y;
        $color->col4 = $k;
        return $color;
    }

    /**
     * @param $r
     * @param $g
     * @param $b
     * @return Color
     */
    public static function rgb($r, $g, $b)
    {
        $color       = new self();
        $color->col1 = $r;
        $color->col2 = $g;
        $color->col3 = $b;
        return $color;

    }

    /**
     * @param $name
     * @return Color
     */
    public static function name($name)
    {
        $color       = new self();
        $color->name = $name;
        return $color;
    }

    public function toArray()
    {
        $arr = [];

        if($this->col1 > -1) $arr[] = $this->col1;
        if($this->col2 > -1) $arr[] = $this->col2;
        if($this->col3 > -1) $arr[] = $this->col3;
        if($this->col4 > -1) $arr[] = $this->col4;

        return $arr;
    }
} 