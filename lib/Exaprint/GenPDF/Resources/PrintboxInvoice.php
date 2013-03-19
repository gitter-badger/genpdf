<?php

namespace Exaprint\GenPDF\Resources;

class PrintboxInvoice extends Invoice
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