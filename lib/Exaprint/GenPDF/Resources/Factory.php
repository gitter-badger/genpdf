<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 04/02/13
 * Time: 23:00
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources;


class Factory {

    /**
     * @param $name
     * @return IResource
     */
    public static function createFromName($name)
    {
        switch ($name)
        {
            case "invoice": return new Invoice();
            case "invoice-statements": return new InvoiceStatements();
            case "supplier-invoice": return new SupplierInvoice();                          // @deprecated
            case "printbox-supplier-invoice": return new PrintboxSupplierInvoice();
            case "printbox-invoice": return new PrintboxInvoice();
            case "printbox-project": return new PrintboxProject();
            case "partners-order": return new PartnersOrder();
            case "order-receipt": return new OrderReceipt();
            case "mandate": return new Mandate();
            case "printbox-rc": return new PrintboxRC();
            case "masterprint-quote": return new MasterprintQuote();
            case "invoice-zip": return new InvoiceZip();
            case "quote" : return new Quote();
            case "contract" : return new Contract();
        }
        return null;
    }
}