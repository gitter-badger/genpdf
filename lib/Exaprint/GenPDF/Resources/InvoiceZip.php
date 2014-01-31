<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 04/02/13
 * Time: 22:58
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources;

use Exaprint\DAL\DB;
use Exaprint\GenPDF\Resources\DAO\Customer;
use Locale\Helper;
use RBM\SqlQuery\Column;
use RBM\SqlQuery\Func;
use RBM\SqlQuery\Select;
use RBM\SqlQuery\Table;

class InvoiceZip extends Resource implements IResource
{

    protected $_data;

    /**
     * @var \SimpleXMLElement
     */
    protected $_xml;

    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id)
    {
        list ($IDClient, $yearAndMonth) = explode('-', $id);
        $this->_year = substr($yearAndMonth, 0, 4);
        $this->_month = substr($yearAndMonth, 4, 2);

        $customerDao = new Customer();
        $this->_customer = $customerDao->getXML($IDClient);

        $select = new Select('TBL_FACTURE', [
            'IDFacture',
        ]);

        $select->filter()
            ->eq('IDClient', $IDClient)
            ->eq(new Func('YEAR', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_year)
            ->eq(new Func('MONTH', [new Column('DateFacture', 'TBL_FACTURE')]), $this->_month);

        $stmt = DB::get()->query($select);

        foreach ($stmt->fetchAll(DB::FETCH_ASSOC) as $invoice) {
            $dao = new DAO\Invoice();
            if ($xml = $dao->getXML($invoice['IDFacture'])) {
                $dataA[] = (array)$xml;
            }
        }

        $this->_data = [
            "invoices" => $dataA,
            "header" => '/static/assets/' . Helper::$current . '/header.png',
            "footer" => '/static/assets/' . Helper::$current . '/footer.png',
        ];

        $this->_xml = (array)$this->_data;

        return true;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return mixed
     */
    public function getTemplateFilename()
    {
        $filename = "resources/";

        switch(\Locale\Helper::$current) {
            case "fr_FR":
                $filename .= "countries/invoice-zip.fr.twig";
                break;
            case "en_GB":
                $filename .= "countries/invoice-zip.en.twig";
                break;
            case "it_IT":
                $filename .= "countries/invoice-zip.it.twig";
                break;
            case "es_ES":
                $filename .= "countries/invoice-zip.es.twig";
                break;
            case "pt_PT":
                $filename .= "countries/invoice-zip.pt.twig";
                break;
            default:
                $filename .= "invoice-zip.twig";
                break;
        }

        return $filename;
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->_xml->asXML();
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

}