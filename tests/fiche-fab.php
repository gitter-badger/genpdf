<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/02/2014
 * Time: 17:08
 */
require '../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
define('K_PATH_FONTS', '../fonts/');

ob_start();


$planche = [
    "IDPlanche" => "310354",
    "commandes" => [
        [
            "IDCommande"          => "1034583",
            "IDPlanche"           => "341354",
            "IDSociete"           => 1,
            "Quantite"            => 368430,
            "ReferenceClient"     => "Dossier n°127-2012 10000ex 100x210mm QRV 170g c ma",
            "CommentairePao"      => "Suite à notre requête concernant la commmande n° 1782402, la réponse de Melle Chloé Pugenc,  et les instructions de vos conseillers, nous lançons un retirage pour le(s) motif(s) suivant(s): défaut de couleur.
Mme Sandrine Poutoux joindra un tirage-témoin qui vous a été envoyé par la poste,  afin de contrôler la conformité des couleurs par rapport à la dernière commande passée chez vous.",
            "BatPapier"           => false,
            "Justificatif"        => true,
            "ServiceTransporteur" => 'TNT J13',
            "CodePostalLivraison" => 'AB1 4ZZ',
            "CodePaysLivraison"   => 'UK',
            "Retirage"            => true,
            "Expedition"          => "12/04",
            "Imperatif"           => false,
            "Rainage"             => '2RA',
            "DateLivraison"       => '02/04',
            "Formats"             => [
                "Ouvert" => [
                    "Largeur"  => 21,
                    "Longueur" => 29.7,
                ],
                "Ferme"  => [
                    "Largeur"  => 10.5,
                    "Longueur" => 29.7
                ],
            ],

            "Visuels"             => [
                [
                    "filename" => "1102324_1-1.jpg",
                    "size"     => 30942,
                    "ext"      => "jpg",
                    "type"     => "thumbnail",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102324/normalized/1102324_1-1.jpg",
                    "width"    => 200,
                    "height"   => 141,
                ],
                [
                    "filename" => "1102324_2-2.jpg",
                    "size"     => 26814,
                    "ext"      => "jpg",
                    "type"     => "thumbnail",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102324/normalized/1102324_2-2.jpg",
                    "width"    => 200,
                    "height"   => 141,
                ],
            ],
        ],
        [
            "IDCommande"          => "1154938",
            "IDPlanche"           => "341354",
            "IDSociete"           => 2,
            "Quantite"            => 142305,
            "ReferenceClient"     => "Dossier n°127-2012 10000ex 100x210mm QRV 170g c ma",
            "CommentairePao"      => "Attention à la coupe",
            "BatPapier"           => true,
            "Justificatif"        => false,
            "ServiceTransporteur" => 'TNT J13',
            "CodePostalLivraison" => '33400',
            "CodePaysLivraison"   => 'FR',
            "Retirage"            => true,
            "Expedition"          => "12/04",
            "Imperatif"           => false,
            "Pliage"              => "1PC",
            "Formats"             => [
                "Ouvert" => [
                    "Largeur"  => 21,
                    "Longueur" => 29.7,
                ]
            ],
            "Visuels"             => [
                [
                    "filename" => "1102321_1-1.jpg",
                    "size"     => 90341,
                    "ext"      => "jpg",
                    "type"     => "normalized",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102321/normalized/1102321_1-1.jpg",
                    "width"    => 225,
                    "height"   => 600,
                ],
            ],
        ],
        [
            "IDCommande"          => "1154938",
            "IDPlanche"           => "341354",
            "Quantite"            => 200,
            "ReferenceClient"     => "CDV",
            "IDSociete"           => 9,
            "CommentairePao"      => "",
            "BatPapier"           => false,
            "Justificatif"        => false,
            "ServiceTransporteur" => 'FEDEX',
            "CodePostalLivraison" => '4455-442',
            "CodePaysLivraison"   => 'PT',
            "Retirage"            => false,
            "Expedition"          => "12/04",
            "Imperatif"           => true,
            "SousTraitance"       => true,
            "Formats"             => [
                "Ouvert" => [
                    "Largeur"  => 10,
                    "Longueur" => 21,
                ],
                "Ferme"  => [
                    "Largeur"  => 10,
                    "Longueur" => 10.5,
                ],
            ],
            "Visuels"             => [
                [
                    "filename" => "1102320_1-1.jpg",
                    "size"     => 228684,
                    "ext"      => "jpg",
                    "type"     => "normalized",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102320/normalized/1102320_1-1.jpg",
                    "width"    => 371,
                    "height"   => 600,
                ],
                [
                    "filename" => "1102320_2-2.jpg",
                    "size"     => 124040,
                    "ext"      => "jpg",
                    "type"     => "normalized",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102320/normalized/1102320_2-2.jpg",
                    "width"    => 371,
                    "height"   => 600,
                ],
            ],
        ],

        [
            "IDCommande"          => "1034583",
            "IDPlanche"           => "341354",
            "IDSociete"           => 1,
            "Quantite"            => 368430,
            "ReferenceClient"     => "Dossier n°127-2012 10000ex 100x210mm QRV 170g c ma",
            "CommentairePao"      => "Suite à notre requête concernant la commmande n° 1782402, la réponse de Melle Chloé Pugenc,  et les instructions de vos conseillers, nous lançons un retirage pour le(s) motif(s) suivant(s): défaut de couleur.
Mme Sandrine Poutoux joindra un tirage-témoin qui vous a été envoyé par la poste,  afin de contrôler la conformité des couleurs par rapport à la dernière commande passée chez vous.",
            "BatPapier"           => false,
            "Justificatif"        => true,
            "ServiceTransporteur" => 'TNT J13',
            "CodePostalLivraison" => 'AB1 4ZZ',
            "CodePaysLivraison"   => 'UK',
            "Retirage"            => true,
            "Expedition"          => "12/04",
            "Imperatif"           => false,
            "Rainage"             => '2RA',
            "DateLivraison"       => '02/04',
            "Formats"             => [
                "Ouvert" => [
                    "Largeur"  => 21,
                    "Longueur" => 29.7,
                ],
                "Ferme"  => [
                    "Largeur"  => 10.5,
                    "Longueur" => 29.7
                ],
            ],

            "Visuels"             => [
                [
                    "filename" => "1102324_1-1.jpg",
                    "size"     => 30942,
                    "ext"      => "jpg",
                    "type"     => "thumbnail",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102324/normalized/1102324_1-1.jpg",
                    "width"    => 200,
                    "height"   => 141,
                ],
                [
                    "filename" => "1102324_2-2.jpg",
                    "size"     => 26814,
                    "ext"      => "jpg",
                    "type"     => "thumbnail",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102324/normalized/1102324_2-2.jpg",
                    "width"    => 200,
                    "height"   => 141,
                ],
            ],
        ],
        [
            "IDCommande"          => "1154938",
            "IDPlanche"           => "341354",
            "IDSociete"           => 2,
            "Quantite"            => 142305,
            "ReferenceClient"     => "Dossier n°127-2012 10000ex 100x210mm QRV 170g c ma",
            "CommentairePao"      => "Suite à notre requête concernant la commmande n° 1782402, la réponse de Melle Chloé Pugenc,  et les instructions de vos conseillers, nous lançons un retirage pour le(s) motif(s) suivant(s): défaut de couleur.
Mme Sandrine Poutoux joindra un tirage-témoin qui vous a été envoyé par la poste,  afin de contrôler la conformité des couleurs par rapport à la dernière commande passée chez vous.",
            "BatPapier"           => true,
            "Justificatif"        => false,
            "ServiceTransporteur" => 'TNT J13',
            "CodePostalLivraison" => '33400',
            "CodePaysLivraison"   => 'FR',
            "Retirage"            => true,
            "Expedition"          => "12/04",
            "Imperatif"           => false,
            "Pliage"              => "1PC",
            "Formats"             => [
                "Ouvert" => [
                    "Largeur"  => 21,
                    "Longueur" => 29.7,
                ]
            ],
            "Visuels"             => [
                [
                    "filename" => "1102321_1-1.jpg",
                    "size"     => 90341,
                    "ext"      => "jpg",
                    "type"     => "normalized",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102321/normalized/1102321_1-1.jpg",
                    "width"    => 225,
                    "height"   => 600,
                ],
            ],
        ],
        [
            "IDCommande"          => "1154938",
            "IDPlanche"           => "341354",
            "Quantite"            => 200,
            "ReferenceClient"     => "CDV",
            "IDSociete"           => 9,
            "CommentairePao"      => "",
            "BatPapier"           => false,
            "Justificatif"        => false,
            "ServiceTransporteur" => 'FEDEX',
            "CodePostalLivraison" => '4455-442',
            "CodePaysLivraison"   => 'PT',
            "Retirage"            => false,
            "Expedition"          => "12/04",
            "Imperatif"           => true,
            "SousTraitance"       => true,
            "Formats"             => [
                "Ouvert" => [
                    "Largeur"  => 10,
                    "Longueur" => 21,
                ],
                "Ferme"  => [
                    "Largeur"  => 10,
                    "Longueur" => 10.5,
                ],
            ],
            "Visuels"             => [
                [
                    "filename" => "1102320_1-1.jpg",
                    "size"     => 228684,
                    "ext"      => "jpg",
                    "type"     => "normalized",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102320/normalized/1102320_1-1.jpg",
                    "width"    => 371,
                    "height"   => 600,
                ],
                [
                    "filename" => "1102320_2-2.jpg",
                    "size"     => 124040,
                    "ext"      => "jpg",
                    "type"     => "normalized",
                    "href"     => "http://fileserver.exaprint.fr/orders/1102320/normalized/1102320_2-2.jpg",
                    "width"    => 371,
                    "height"   => 600,
                ],
            ],
        ],
    ]
];


$ff = new \Exaprint\GenPDF\FicheDeFabrication\Amalgame($planche);

if (!isset($_GET['render'])) {
    header('Content-Type: text/plain');
    ob_end_flush();
} else {
    ob_end_clean();
    header('Content-Type: application/x-pdf');
    echo $ff->pdf->Output();
}