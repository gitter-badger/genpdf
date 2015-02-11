<?php

namespace Locale;

class TwigExtension_I18nParser extends \Twig_TokenParser
{
    public function parse(\Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $value = $parser->getExpressionParser()->parseExpression();
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new \Locale\TwigExtension_I18nNode($value, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'trans';
    }
}