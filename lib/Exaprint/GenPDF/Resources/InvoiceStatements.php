<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\DB;
use Exaprint\DAL\Select;
use Exaprint\GenPDF\Resources\DAO\Customer;
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

        $customerDao     = new Customer();
        $this->_customer = $customerDao->getXML($IDClient);

        $select = new Select('TBL_FACTURE', [
            'InvoiceID'     => 'IDFacture',
            'InvoiceDate'   => 'DateFacture',
            'ETAmount'      => 'MontantHT',
            'ATIAmount'     => 'MontantTTC',
            'VATAmount'     => 'MontantTVA',
            'Reference'     => 'ReferenceFacture',
            'InvoiceNumber' => 'NumeroFacture',
        ]);
        $select->filter()
            ->eq('IDClient', $IDClient)
            ->eq(new Func('YEAR', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_year)
            ->eq(new Func('MONTH', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_month);

        $stmt = DB::get()->query($select);


        foreach ($stmt->fetchAll(DB::FETCH_ASSOC) as $invoice) {

            $this->_lines[] = $invoice;

            $this->_sums['ati'] += $invoice["ATIAmount"];
            $this->_sums['et'] += $invoice["ETAmount"];
            $this->_sums['vat'] += $invoice["VATAmount"];
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
        return Helper::$current . "/header.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return Helper::$current . "/footer.html";
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