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
} 