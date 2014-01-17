<?php

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\DB;
use Exaprint\DAL\Select;
use Exaprint\GenPDF\Resources\DAO\Customer;
use Locale\Helper;
use RBM\SqlQuery\Column;
use RBM\SqlQuery\Func;
use RBM\SqlQuery\Table;

class InvoiceStatements extends Resource implements IResource
{
    const TYPE_INVOICE = 1;
    const TYPE_CREDIT  = 2;

    protected $_customer;
    protected $_invoices = [];
    protected $_credits = [];
    protected $_month;
    protected $_year;

    protected $_invoicesSums = [
        'ati' => 0,
        'vat' => 0,
        'et'  => 0
    ];
    protected $_creditsSums = [
        'ati' => 0,
        'vat' => 0,
        'et'  => 0
    ];

    protected $_netAmountDue = 0;

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
            'Type'          => 'TypeFacture',
            'InvoiceDate'   => 'DateFacture',
            'ETAmount'      => 'MontantHT',
            'ATIAmount'     => 'MontantTTC',
            'VATAmount'     => 'MontantTVA',
            'Reference'     => 'ReferenceFacture',
            'InvoiceNumber' => 'NumeroFacture',
            'DirectDebitDate' => 'DatePrelevement',
        ]);

        $commande = $select->leftJoin('TBL_COMMANDE', 'IDFacture')->cols([
            'OrderReference' => 'ReferenceClient',
            'OrderDate' => 'DateCommande',
            'IDModeReglement' => 'IDModeReglement'
        ]);

        $payment = $commande->leftJoin('TBL_MODEREGLEMENT_TRAD');
        $payment->joinCondition()
            ->eq('IDModeReglement', new Column('IDModeReglement', new Table('TBL_COMMANDE')))
            ->eq('IDLANGUE', 1);
        $payment->cols([
            'payment' => 'LibelleTraduit'
        ]);

        $select->filter()
            ->eq('IDClient', $IDClient)
            ->eq(new Func('YEAR', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_year)
            ->eq(new Func('MONTH', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_month);

        //echo $select;

        $stmt = DB::get()->query($select);


        foreach ($stmt->fetchAll(DB::FETCH_ASSOC) as $invoice) {

            switch ($invoice['Type']) {
                case self::TYPE_INVOICE :
                    $this->_invoices[] = $invoice;

                    $this->_invoicesSums['ati'] += $invoice["ATIAmount"];
                    $this->_invoicesSums['et'] += $invoice["ETAmount"];
                    $this->_invoicesSums['vat'] += $invoice["VATAmount"];
                    break;
                case self::TYPE_CREDIT :

                    $this->_credits[] = $invoice;

                    $this->_creditsSums['ati'] += $invoice["ATIAmount"];
                    $this->_creditsSums['et'] += $invoice["ETAmount"];
                    $this->_creditsSums['vat'] += $invoice["VATAmount"];
            }
        }

        $this->_netAmountDue = $this->_invoicesSums['ati'] - $this->_creditsSums['ati'];


        return true;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            "Year"         => $this->_year,
            "Month"        => $this->_month,
            "Invoices"     => $this->_invoices,
            "InvoicesSums" => $this->_invoicesSums,
            "Credits"      => $this->_credits,
            "CreditsSums"  => $this->_creditsSums,
            "Customer"     => (array)$this->_customer,
            "NetAmountDue" => $this->_netAmountDue,
            "Footer"       => $this->getFooter(),
        ];
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->_imageFolder . Helper::$current . "/header.html";
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return $this->_imageFolder . Helper::$current . "/footer.html";
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