<?php

namespace Exaprint\GenPDF\Resources\DAO;

use RBM\SqlQuery\Renderer\SqlServer;
use RBM\SqlQuery\Select;

class PrintboxProject
{
    const INDENT_TOKEN      = ".";
    const GROUP_OPEN_TOKEN  = "(";
    const GROUP_CLOSE_TOKEN = ")";

    public function fetchFromId($IDCommande, $IDLangue = 1)
    {
        Select::setDefaultRenderer(new SqlServer());

        $select = new Select("TBL_TL_COMMANDE_PRINTBOX", [
            "order.id"           => "IDCommande",
            "project.ati_amount" => "MontantTTC",
            "project.vat_amount" => "MontantTVA",
            "project.et_amount"  => "MontantHT",
            "client.id"          => "IDClient",
        ]);

        $finalClient = $select->join(["client" => "VUE_INFOS_CLIENT"], "IDClient")
            ->cols([
                "client.company_name"     => "RaisonSociale",
                "client.email"            => "MailFacturation",
                "client.address.line1"    => "Adresse1",
                "client.address.line2"    => "Adresse2",
                "client.address.line3"    => "Adresse3",
                "client.address.postcode" => "CodePostal",
                "client.address.city"     => "Ville",
                "client.contact_name"     => "NomContact",
                "client.contact_forename" => "PrenomContact",
            ]);


        $commande = $select->join("TBL_COMMANDE", "IDCommande")
            ->cols([
                "order.reference"  => "ReferenceClient",
                "order.ati_amount" => "MontantTTC",
                "order.vat_amount" => "MontantTVA",
                "order.et_amount"  => "MontantHT",
            ]);


        $p = $commande->join("TBL_COMMANDE_LIGNE", "IDCommande")
            ->join("TBL_PRODUIT", "IDProduit");

        $p->join("TBL_PRODUIT_LIBELLE_FRONT_TRAD", "IDProduit")
            ->cols(["product.name" => "LibelleTraduit"])
            ->joinCondition()->eq("IDLangue", $IDLangue);

        $pfp = $p->join("TBL_PRODUIT_FAMILLE_PRODUIT", "IDProduitFamilleProduit");

        $pfp->join("TBL_PRODUIT_FAMILLE_PRODUIT_DESIGNATION_TRADUCTION", "IDProduitFamilleProduit")
            ->cols(["product.subfamily" => "LibelleTraduit"])
            ->joinCondition()->eq("IDLangue", $IDLangue);

        $pfa = $pfp->join("TBL_PRODUIT_FAMILLE_ARTICLES", "IDProduitFamilleArticles");

        $pfa->join("TBL_PRODUIT_FAMILLE_ARTICLES_TRAD", "IDProduitFamilleArticles")
            ->cols(["product.family" => "LibelleTraduit"])
            ->joinCondition()->eq("IDLangue", $IDLangue);


        $printboxer = $commande->join(["pboxer" => "VUE_INFOS_CLIENT"], "IDClient")
            ->cols([
                "pboxer.company_name"     => "RaisonSociale",
                "pboxer.email"            => "MailFacturation",
                "pboxer.address.line1"    => "Adresse1",
                "pboxer.address.line2"    => "Adresse2",
                "pboxer.address.line3"    => "Adresse3",
                "pboxer.address.postcode" => "CodePostal",
                "pboxer.address.city"     => "Ville",
                "pboxer.contact_name"     => "NomContact",
                "pboxer.contact_forename" => "PrenomContact",
            ]);

        $select->filter()->eq("IDCommande", $IDCommande);

        $db = new Database("test");
        $data = array();
        if ($r = $db->query($select)) {

            $data = $this->_indent($r->fetchAll(\PDO::FETCH_OBJ), "order.id");

            array_walk($data, function(&$row, $orderId) use ($db, $IDLangue) {

                $frais = new Select("TBL_FRAIS", [
                    "quantity"    => "Quantite",
                    "unit_amount" => "MontantUnitaireHT",
                    "ati_amount"  => "MontantTTC",
                    "et_amount"   => "MontantHT",
                    "vat_amount"  => "MontantTVA",
                ]);

                $frais->filter()->eq("IDCommande", $orderId);

                $fraisTrad = $frais->join("TBL_FRAIS_TYPE_FRAIS_TRAD", "IDFraisTypeFrais")
                    ->cols(["type" => "LibelleTraduit"]);
                $fraisTrad
                    ->joinCondition()->eq("IDLangue", $IDLangue);

                if ($r = $db->query($frais)) {
                    $row->order->fees = $r->fetchAll(\PDO::FETCH_OBJ);
                }
            });
        } else {
            var_dump($r);
            var_dump($db->errorCode());
            var_dump($db->errorInfo());

        }
        return $data;
    }


    /**
     * Converts aaa.bbb onto ->aaa->bbb
     * @param $row
     * @return \stdClass
     */
    protected function _indent($rows, $groupCol)
    {

        $groupColValue = "";
        $results       = array();


        foreach ($rows as $row) {
            if ($row->$groupCol !== $groupColValue) {
                $groupColValue           = $row->$groupCol;
                $results[$groupColValue] = (object)array();
                $data                    = & $results[$groupColValue];
            }
            foreach ($row as $field => $value) {

                $parent = & $data;

                //echo "<h1>$field</h1>";
                $buffer = "";

                $pos = null;

                $inGroupBy        = false;
                $groupByArray     = null;
                $groupByKey       = array();
                $groupByValue     = null;
                $lastGroupByValue = null;

                for ($i = 0; $i < strlen($field); $i++) {
                    if (substr($buffer, -strlen(self::GROUP_OPEN_TOKEN)) === self::GROUP_OPEN_TOKEN) {

                        $token          = substr($buffer, 0, -strlen(self::GROUP_OPEN_TOKEN));
                        $parent->$token = array();
                        $groupByArray   = & $parent->$token;
                        $groupByKey     = array();
                        $inGroupBy      = true;
                        $buffer         = "";

                    } else if (substr($buffer, -strlen(self::GROUP_CLOSE_TOKEN)) === self::GROUP_CLOSE_TOKEN) {

                        $token = substr($buffer, 0, -strlen(self::GROUP_CLOSE_TOKEN));
                        //echo "token found : $token <br/>";
                        $inGroupBy    = false;
                        $groupByKey[] = $token;
                        $root         = & $data;
                        foreach ($groupByKey as $key) {
                            $root = & $root->$key;
                        }
                        $groupByValue                = (string)$root;
                        $groupByArray[$groupByValue] = (object)array();
                        $parent                      =& $groupByArray[$groupByValue];

                        var_dump($data);
                        $buffer = "";

                    } else if (substr($buffer, -strlen(self::INDENT_TOKEN)) === self::INDENT_TOKEN) {

                        $token = substr($buffer, 0, -strlen(self::INDENT_TOKEN));
                        //echo "token found : $token <br/>";

                        if ($token !== '') {
                            if (!$inGroupBy) {
                                if (!isset($parent->$token)) {
                                    $parent->$token = (object)array();
                                }
                                $parent = & $parent->$token;
                            } else {
                                $groupByKey[] = $token;
                            }
                        }
                        $buffer = "";

                    }
                    $buffer .= $field[$i];
                }

                $parent->$buffer = $value;
            }
        }
        return $results;
    }

}