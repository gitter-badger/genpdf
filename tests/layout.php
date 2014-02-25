<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/02/2014
 * Time: 15:10
 */
require '../vendor/autoload.php';

$l = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame\Layout();
//
//print_r(
//    [
//        [$l->xBloc(0), $l->yBloc(0)],
//        [$l->xBloc(1), $l->yBloc(1)],
//        [$l->xBloc(2), $l->yBloc(2)],
//        [$l->xBloc(3), $l->yBloc(3)],
//    ]
//);

echo json_encode($l, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);