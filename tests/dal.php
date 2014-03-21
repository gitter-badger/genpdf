<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21/02/2014
 * Time: 16:03
 */

require "/Users/admin/Sites/env.php";
$_SERVER['exaprint_env'] = 'stage';
require "../vendor/autoload.php";

$planche = \Exaprint\GenPDF\FicheDeFabrication\DAL::getPlanche(348552);

print_r($planche);

