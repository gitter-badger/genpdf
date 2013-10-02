<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxSupplierInvoice extends SupplierInvoice
{
    public function getTemplateFilename()
    {
        return "resources/printbox-supplier-invoice.twig";
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return "header.empty.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return false;
    }


}