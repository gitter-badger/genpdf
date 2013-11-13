<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\DB;

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
        $db = DB::get();
        $query = "select * from [Sc_Front].[EXP_CLIENT_PREFERENCES] where [IDClient]='" . $this->_id . "'";
        if ($result = $db->query($query)) {
            $data = $result->fetch();
            if ($data && $data->Logo) {
                return "/mnt/nfs/Prod/Exapass/data_exapass/" . $data->Logo;
            }
        }
        return $this->_imageFolder . "header.empty.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->_imageFolder . "footer.empty.html";
    }


}