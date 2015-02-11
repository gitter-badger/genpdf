<?php

namespace Locale;

/**
 * Class TwigExtension_I18n
 * @package Locale
 * @see the official trans twig extension for help : Twig_Extensions_Extension_I18n()
 * @see the official help for building a twig extension for help : http://twig.sensiolabs.org/doc/advanced.html#defining-a-token-parser
 */
class TwigExtension_I18n extends \Twig_Extension
{
    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return array(new \Locale\TwigExtension_i18nParser());
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'trans' => new \Twig_SimpleFilter('trans', function($value) {
                    return t($value);
                }),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'i18n';
    }
}
