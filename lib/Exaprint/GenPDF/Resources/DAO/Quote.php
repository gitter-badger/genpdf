<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 13/06/14
 * Time: 14:13
 */

namespace Exaprint\GenPDF\Resources\DAO;
use Exaprint\DAL\DB;
use RBM\SqlQuery\Select;
use RBM\SqlQuery\Table;

class Quote {
    /**
     * @param $idQuote
     * @param $quantity
     * @return array
     */
    public function fetchFromId($idQuote,$quantity)
    {
        $select = new Select();
        $select->setTable(new Table('TBL_DEVIS','Sc_Masterprint'));
        $select->where()
            ->eq('NumeroDevis', $idQuote)
            ->eq('Quantite', $quantity);
        $stmt = DB::get()->query($select);
        $data = $stmt->fetch();

        return $data;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getDepartementNameByCode($code){
        $select = new Select();
        $select->setTable(new Table('TBL_DEPARTEMENTS','dbo'));
        $select->where()
            ->eq('NumeroDepartement',$code);
        $stmt = DB::get()->query($select);
        $data = $stmt->fetch();

        return $data;
    }

    public function getCustomerInfos($idCustomer){
        $result=[];
        $select = new Select();
        $select->setTable(new Table('TBL_CLIENT','dbo'));
        $contact = $select->join(new Table('TBL_CLIENT_CONTACT','dbo'),'IdClient','IdClient');
        $contact->cols(['NomContact'],['CiviliteContact']);
        $contact->where()
            ->eq('ContactPrincipal',1);
        $select->cols(['RaisonSociale']);
        $select->where()
            ->eq('IdClient',$idCustomer);

        $stmt = DB::get()->query($select);
        $data = $stmt->fetch();

        if($data !== false){
            $result['raisonSociale'] = $data->RaisonSociale;
            $result['nomContact'] = $data->NomContact;
            $result['civilite'] = $data->CiviliteContact;
        }
        return $result;
    }
} 