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
        return $this->_imageFolder . "printbox/header.html";
    }

    public function getFooter()
    {
        return $this->_imageFolder . Helper::$current . "/footer.html";
    }


}