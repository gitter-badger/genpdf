<?php

namespace Exaprint\GenPDF\Resources;

use Locale\Helper;

class PrintboxInvoice extends Invoice
{
    public function getTemplateFilename()
    {
        return "resources/printbox-invoice.twig";
    }

    public function getHeader()
    {
        return "printbox/header.html";
    }

    public function getFooter()
    {
        return Helper::$current . "/footer.svg";
    }


}