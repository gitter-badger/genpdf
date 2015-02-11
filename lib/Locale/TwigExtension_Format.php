<?php

namespace Locale;

class TwigExtension_Format extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return "Format";
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
            new \Twig_SimpleFilter('formatnumber', function($value){
                return Helper::formatNumber($value);
            }, ["is_safe" => ["html"]]),
        ];
    }
}