<?php

namespace Locale;

class TwigExtension_I18nNode extends \Twig_Node
{
    public function __construct(\Twig_Node_Expression $value, $line, $tag = null)
    {
        parent::__construct(array('value' => $value), array(), $line, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('echo t(')
            ->subcompile($this->getNode('value'))
            ->raw(");\n")
        ;
    }
}