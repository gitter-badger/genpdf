<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxInvoice extends Invoice
{
    public function getTemplateFilename()
    {
        return "resources/printbox/invoice.twig";
    }

    public function getHeader()
    {
        return "printbox/header.html";
    }

    public function getFooter()
    {
        return "printbox/footer.html";
    }


}