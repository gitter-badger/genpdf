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
            case "supplier-invoice": return new SupplierInvoice();
            case "printbox-supplier-invoice": return new PrintboxSupplierInvoice();
            case "printbox-project": return new PrintboxProject();
        }
        return null;
    }
}