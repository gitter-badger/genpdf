<?php

namespace Locale;

class TwigExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return "Locale";
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('formatdate', function($value){
                return Helper::formatDate($value);
            }, ["is_safe" => ["html"]]),
            new \Twig_SimpleFilter('formatmoney', function($value){
                return Helper::formatMoney($value);
            }, ["is_safe" => ["html"]]),
        ];
    }
}