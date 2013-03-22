<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxSupplierInvoice extends SupplierInvoice
{
    public function getTemplateFilename()
    {
        return "resources/printbox/supplier-invoice.twig";
    }


}