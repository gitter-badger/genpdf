<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\DB;
use Exaprint\DAL\Select;
use Locale\Helper;
use RBM\SqlQuery\Column;
use RBM\SqlQuery\Func;

class InvoiceStatements implements IResource
{
    protected $_customer;
    protected $_invoices = [];
    protected $_lines = [];
    protected $_month;
    protected $_year;

    protected $_sums = [
        'ati' => 0,
        'vat' => 0,
        'et'  => 0
    ];

    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
        list ($IDClient, $yearAndMonth) = explode('-', $id);
        $this->_year  = substr($yearAndMonth, 0, 4);
        $this->_month = substr($yearAndMonth, 4, 2);

        $select = new Select('TBL_FACTURE', ['IDFacture']);
        $select->filter()
            ->eq('IDClient', $IDClient)
            ->eq(new Func('YEAR', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_year)
            ->eq(new Func('MONTH', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_month);

        $stmt = DB::get()->query($select);


        foreach ($stmt->fetchAll(DB::FETCH_COLUMN) as $invoiceId) {
            $invoice = new Invoice();
            $invoice->fetchFromID($invoiceId);
            $data = $invoice->getData();

            if ($data["Type"] == 1) {
                $this->_invoices[] = $invoice;
                $this->_lines[]    = [
                    "Reference"   => $data["Reference"],
                    "InvoiceDate" => $data["InvoiceDate"],
                    "ETAmount"    => $data["ETAmount"],
                    "VATAmount"   => $data["VATAmount"],
                    "ATIAmount"   => $data["ATIAmount"]
                ];

                $this->_sums['ati'] += $data["ATIAmount"];
                $this->_sums['et'] += $data["ETAmount"];
                $this->_sums['vat'] += $data["VATAmount"];
            }


            $this->_customer = $data["Customer"];
        }
        return true;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            "Year"     => $this->_year,
            "Month"    => $this->_month,
            "Lines"    => $this->_lines,
            "Invoices" => $this->_invoices,
            "Sums"     => $this->_sums,
            "Customer" => (array)$this->_customer,
        ];
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return Helper::$current  . "/header.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return Helper::$current  . "/footer.html";
    }


    /**
     * @return mixed
     */
    public function getTemplateFilename()
    {
        return 'resources/invoice-statements.twig';
    }

    /**
     * @return string
     */
    public function getXml()
    {
        // TODO: Implement getXml() method.
    }

}