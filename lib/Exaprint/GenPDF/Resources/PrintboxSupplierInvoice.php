<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxSupplierInvoice extends SupplierInvoice
{
    public function getHeader()
    {
        return "header.printbox.html";
    }

    public function getFooter()
    {
        return "footer.printbox.html";
    }


}