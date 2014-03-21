<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 19/02/2014
 * Time: 16:07
 */

namespace Exaprint\TCPDF;


class CellPadding implements DrawingContext
{

    public $top;

    public $right;

    public $bottom;

    public $left;

    public $prev;

    function __construct($top = '', $right = '', $bottom = '', $left = '')
    {
        $this->bottom = $bottom;
        $this->left   = $left;
        $this->right  = $right;
        $this->top    = $top;
    }

    public static function all($value)
    {
        return new self($value, $value, $value, $value);
    }

    public static function top($value)
    {
        return new self($value);
    }

    public static function right($value)
    {
        return new self('', $value);
    }

    public static function bottom($value)
    {
        return new self('', '', $value);
    }

    public static function left($value)
    {
        return new self('', '', '', $value);
    }


    public function apply(\TCPDF $pdf)
    {
        $this->prev = $pdf->getCellPaddings();
        $pdf->SetCellPaddings($this->left, $this->top, $this->right, $this->bottom);
    }

    public function revert(\TCPDF $pdf)
    {
        $pdf->SetCellPaddings(
            $this->prev['L'],
            $this->prev['T'],
            $this->prev['R'],
            $this->prev['B']
        );
    }


} 