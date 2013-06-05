<?php

require '../vendor/autoload.php';
require '/Users/rbm/Sites/exaprint/env.php';

$statements = new \Exaprint\GenPDF\Resources\InvoiceStatements();
$statements->fetchFromID('2130-201304');