<?php

namespace Exaprint\GenPDF\FicheDeFabrication\GFN;

use Exaprint\GenPDF\FicheDeFabrication\Amalgame\DAL;

class GFNDAL extends DAL
{

    public static function getImagesFichiers($orderId)
    {

        $raw     = file_get_contents("http://fileserver.exaprint.fr/orders/$orderId");
        $json    = json_decode($raw, true);
        $visuels = [];
        foreach ($json as $fichier) {
            if ($fichier['type'] == 'normalized'
                && $fichier['ext'] == 'jpg'
            ) {
                // Format attendu : 2685438-9_1-1.jpg
                $nbr = str_replace($orderId . '-', '', $fichier['filename']);
                if ($nbr == $fichier['filename']) {
                    continue;
                }
                $nbr = explode('_', $nbr)[0];
                $visuels[$nbr - 1] = $fichier;
            }
        }

        return $visuels;
    }

}