<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 21/08/13
 * Time: 14:44
 * To change this template use File | Settings | File Templates.
 */

require '../vendor/autoload.php';
require '/Users/rbm/Sites/exaprint/env.php';

$db = \Exaprint\DAL\DB::get('prod');
$db->exec('SET QUOTED_IDENTIFIER ON');
$db->exec('SET ANSI_WARNINGS ON');
$db->exec('SET ANSI_PADDING ON');
$db->exec('SET ANSI_NULLS ON');
$db->exec('SET CONCAT_NULL_YIELDS_NULL ON');
function test(\Exaprint\DAL\DB $db, $query)
{
    echo PHP_EOL . $query;
    $stmt = $db->query($query);
    if ($stmt) {
        var_dump($stmt->fetchColumn());
    } else {
        print_r($db->errorInfo());
    }

}


test($db, "select CAST(dbo.f_XML_Facture(126859) AS nvarchar(max))");
test($db, "select CAST(dbo.f_XML_Facture(126859) AS XML)");
test($db, "select CAST(dbo.f_XML_Facture(126859) AS TEXT)");
test($db, "select CONVERT(TEXT, dbo.f_XML_Facture(126859))");
test($db, "select dbo.f_XML_Facture(126859)");
test($db, "select CONVERT( xml, dbo.f_XML_Facture(126859))");
test($db, "select CONVERT( VARCHAR(max), dbo.f_XML_Facture(126859))");