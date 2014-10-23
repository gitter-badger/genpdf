<?php

require '../bootstrap.php';

if (strpos($_SERVER['SERVER_NAME'], 'local')) {
    \RBM\Wkhtmltopdf\Wkhtmltopdf::$bin = '/usr/local/Cellar/wkhtmltopdf/0.11.0_rc1/bin/wkhtmltopdf';
}

$app->get("/", function () {
    var_dump(\Exaprint\DAL\DB::get()->getDefaultEnv());
});

$app->get('/fiche-de-fab/:IDPlanche', function ($IDPlanche) use ($app) {
    $p = \Exaprint\GenPDF\FicheDeFabrication\DAL::getPlanche($IDPlanche);

    if (is_null($p)) {
        $app->status(404);
        return;
    }

    $cacheSubFolder = round($IDPlanche / 100) * 100;
    $cachePath      = '../cache/fiche-de-fab/' . $cacheSubFolder;

    if (!file_exists($cachePath)) {
        mkdir($cachePath, 0777);
    }

    $cacheFilename = $cachePath . '/' . $IDPlanche . '.pdf';

    if (file_exists($cacheFilename) && is_null($app->request->get('norender'))) {
        header('Content-Type: application/x-pdf');
        echo file_get_contents($cacheFilename);
    }

    if ($ff = \Exaprint\GenPDF\FicheDeFabrication\Factory::createFicheDeFabrication($p)) {

        if ($app->request->get('norender')) {
            header('Content-Type: text/html');
            ob_end_flush();
        } else {
            ob_end_clean();
            header('Content-Type: application/x-pdf');
            $ff->pdf->Output($cacheFilename, 'FI');
        }
    }
});

$app->get("/:name/:id.xml", function ($name, $id) use ($app) {
    $app->contentType('text/xml');
    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);
    if ($resource && $resource->fetchFromID($id)) {
        echo $resource->getXml();
    }
});

$app->get("/:name/:id.pdf", function ($name, $id) use ($app) {

    $language = \Locale\Helper::$current;

    $app->contentType("application/pdf");

    // Organiser le cache
    if ($name == 'invoice-statements') {
        // classer par mois AAAAMMM
        $infos     = explode('-', $id);
        $subfolder = $infos[1];
    } else {
        // classer par 1000
        $subfolder = (floor($id / 1000) + 1) * 1000;
    }

    // Chemin final
    $filename = "../cache/{$name}/{$subfolder}/{$name}_{$id}";

    if ($name != 'mandate' && $name != 'printbox-rc') {
        $filename .= "_{$language}";
    }

    $filename .= ".pdf";

    // Si un cache existe, le
    if (file_exists($filename) && !isset($_GET['nocache']) && $name != 'mandate') {
        echo file_get_contents($filename);
        return;
    }

    deleteTemplateCache();

    // Création de dossier
    if (!file_exists("../cache/{$name}")) {
        mkdir("../cache/{$name}", 0777);
    }
    if (!file_exists("../cache/{$name}/{$subfolder}")) {
        mkdir("../cache/{$name}/{$subfolder}", 0777);
    }

    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);

    if ($resource) {

        $wkhtml = new \RBM\Wkhtmltopdf\Wkhtmltopdf();

        if ($name != 'invoice-zip') {
            $wkhtml->setHeaderHtml($resource->getHeader());
            $wkhtml->setMarginTop(40);
            $wkhtml->setHeaderSpacing(5);
            $wkhtml->setFooterHtml($resource->getFooter());
            $wkhtml->setFooterSpacing(5);
            $wkhtml->setMarginBottom(49);
        }

        $r = $wkhtml->run($_SERVER["SERVER_NAME"] . "/$name/$id.html", $filename);

        if ($r['return'] == '0') {
            echo file_get_contents($filename);
        } else {
            $app->contentType('text/plain');
            $app->status(404);
            echo "Impossible de fournir la ressource : " . $r['cmd'];
        }
    }
});

$app->get("/:name/:id.html", function ($name, $id) use ($app) {

    deleteTemplateCache();

    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);

    if ($resource && $resource->fetchFromID($id)) {
        $app->render(
            $resource->getTemplateFilename(),
            $resource->getData()
        );
        return;
    }

    $app->status(404);
    echo "Impossible de fournir la ressource de type $name #$id";

});

$app->post("/tnt-express-connect/:type", function ($type) use ($app) {
    if (in_array($type, array("invoice", "label", "manifest", "connote"))) {
        $app->contentType("application/x-pdf");
        xsltProcess($app->request()->post("xml"));
        return;
    }
    $app->contentType("text/plain");
    $app->status(404);
    echo "Type de document ExpressConnect introuvable : '$type'";
});

$app->get("/tnt-express-connect/:type", function () use ($app) {
    echo '
    <form method=post>
        <textarea name="xml" cols="30" rows="10"></textarea>
        <input type="submit"/>
    </form>';
});


$app->get("/locale", function () use ($app) {

});

$app->get('/static/assets/dynamic-footer', function () use ($app) {
    echo '<html><head></head><body><div class="foot">' . $app->request()->get('string') . '</div></body></html>';
});

function htmlReplace($lFile,$directory){
    $lContents = file_get_contents($directory."/".$lFile);
    $lResult = array();
    preg_match_all("/<img.*src=\"([^\"]*)\"[^>]*>/", $lContents, $lResult, PREG_OFFSET_CAPTURE);
    $lCaptures = $lResult[1];
    $lImages = array();
    $lOffsetOffset = 0;
    foreach($lCaptures as $lCapture) {
        $lUrl = html_entity_decode($lCapture[0]);
        $lOfsset = $lCapture[1] - $lOffsetOffset;
        $lKey = md5($lUrl);
        if(isset($lImages[$lKey])) {
            $lFilename = $lImages[$lKey];
            $lOffsetOffset = strlen($lCapture[0]) - strlen($lFilename);
            $lHtmlStart = substr($lContents, 0, $lOfsset);
            $lHtmlEnd = substr($lContents, $lOfsset + strlen($lCapture[0]));
            $lContents = $lHtmlStart . $lFilename . $lHtmlEnd;
        } else {
            $lImgContents = @file_get_contents($lUrl);
            if($lImgContents !== false) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $lType =  $finfo->buffer($lImgContents);
                $lFilename = sprintf("%s.%s", $lKey, substr(strrchr($lType, "/"),1));
                file_put_contents($directory."/".$lFilename, $lImgContents);
                $lImages[$lKey] = $lFilename;
                $lOffsetOffset = strlen($lCapture[0]) - strlen($lFilename);
                $lHtmlStart = substr($lContents, 0, $lOfsset);
                $lHtmlEnd = substr($lContents, $lOfsset + strlen($lCapture[0]));
                $lContents = $lHtmlStart . $lFilename . $lHtmlEnd;
            }
        }
    }

    $lContents = str_replace("<script type=\"text/Javascript\">includePageBreak();</script>","</div><div class=\"pageDiv\">",$lContents);
    $lContents = str_replace("document.writeln('</div>');","",$lContents);
    $lContents = str_replace("document.writeln('<div class=\"pagebreak\">');","",$lContents);
    $lContents = str_replace("div { page-break-after : always; }",".pageDiv{height:940px;page-break-inside: avoid;}",$lContents);
    $lContents = str_replace("</body>","</div></body>",$lContents);

    //Supprime la première occurence de <\div>
    $lContents = preg_replace('/\<\/div>/', '', $lContents, 1 );
    file_put_contents($directory."/".$lFile, $lContents);
}

function xsltProcess($xml)
{
    $filename = "/tmp/" . uniqid("genpdf");
    file_put_contents("$filename.xml", $xml);
    $cmd = "xsltproc $filename.xml > $filename.html";
    exec($cmd, $output, $return);

    htmlReplace(basename($filename).".html","/tmp");

    $wkhtml = new \RBM\Wkhtmltopdf\Wkhtmltopdf();
    $return = $wkhtml->run("$filename.html", "$filename.pdf");
    if (file_exists("$filename.pdf")) {
        echo file_get_contents("$filename.pdf");
        unlink("$filename.pdf");
    } else {
        var_dump($return);
    }

    unlink("$filename.xml");
    unlink("$filename.html");
}


$app->post('/cgf', function () use ($app, $twig) {
    $twig = $twig->getInstance();
    $data = json_decode($app->request()->getBody());
    $quote    = new Exaprint\GenPDF\CGF\Quote($data);
    $language = $quote->getCollation();
    putenv("LC_MESSAGES=" . $language);
    setlocale(LC_MESSAGES, $language);
    if (function_exists('bindtextdomain') && function_exists('textdomain')) {
        bindtextdomain("messages", APPLICATION_ROOT . "/locale");
        textdomain("messages");
        bind_textdomain_codeset("messages", "UTF-8");
    }

    ob_start();
    $twig->display('cgf/quote.twig', ['quote' => $quote]);
    $html = ob_get_clean();
    $uid      = uniqid();
    $filename = "cgf-quote-$uid";
    file_put_contents(__DIR__ . "/temp/$filename.html", $html);

    $wkhtml = new \RBM\Wkhtmltopdf\Wkhtmltopdf();
    $wkhtml->setHeaderHtml("http://$_SERVER[SERVER_NAME]/static/assets/$language/header.html");
    $wkhtml->setMarginTop(40);
    $wkhtml->setHeaderSpacing(5);
    $wkhtml->setFooterHtml("http://$_SERVER[SERVER_NAME]/static/assets/$language/footer.html");
    $wkhtml->setFooterSpacing(5);
    $wkhtml->setMarginBottom(49);

    $r = $wkhtml->run($_SERVER["SERVER_NAME"] . "/temp/$filename.html", __DIR__ . "/temp/$filename.pdf");

    if ($r['return'] == '0') {
        echo file_get_contents(__DIR__ . "/temp/$filename.pdf");
    }
});

$app->run();