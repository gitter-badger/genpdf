<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxSupplierInvoice extends SupplierInvoice
{
    public function getTemplateFilename()
    {
        return "resources/printbox/supplier-invoice.twig";
    }


    public function getHeader()
    {
        return "header.printbox.html";
    }

    public function getFooter()
    {
        return "footer.printbox.html";
    }


}