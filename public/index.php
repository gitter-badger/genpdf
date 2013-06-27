<?php

require '../bootstrap.php';

if (strpos($_SERVER['SERVER_NAME'], 'local')) {
    \RBM\Wkhtmltopdf\Wkhtmltopdf::$bin = '/usr/local/Cellar/wkhtmltopdf/0.11.0_rc1/bin/wkhtmltopdf';
}

$app->get("/", function () {
    var_dump(\Exaprint\DAL\DB::get()->getDefaultEnv());
});

$app->get("/:name/:id.xml", function ($name, $id) use ($app) {
    $app->contentType('text/xml');
    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);
    if ($resource && $resource->fetchFromID($id)) {
        echo $resource->getXml();
    }
});

$app->get("/:name/:id.pdf", function ($name, $id) use ($app) {

    $app->contentType("application/pdf");
    $filename = "../cache/{$name}_{$id}.pdf";

    if (file_exists($filename)) {
        echo file_get_contents($filename);
        return;
    }

    deleteTemplateCache();

    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);

    if ($resource) {

        $wkhtml = new \RBM\Wkhtmltopdf\Wkhtmltopdf();

        $wkhtml->setHeaderHtml($_SERVER["SERVER_NAME"] . "/static/assets/" . $resource->getHeader());
        $wkhtml->setMarginTop(46);

        $wkhtml->setFooterHtml($_SERVER["SERVER_NAME"] . "/static/assets/" . $resource->getFooter());
        $wkhtml->setFooterSpacing(0);
        $wkhtml->setMarginBottom(40);

        $r = $wkhtml->run($_SERVER["SERVER_NAME"] . "/$name/$id.html", $filename);

        if ($r['return'] == '0') {
            echo file_get_contents($filename);
        } else {
            $app->contentType('text/plain');
            $app->status(500);
            echo $r['cmd'];
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
    echo "Impossible de trouver la ressource de type $name #$id";

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


function xsltProcess($xml)
{
    $filename = "/tmp/" . uniqid("genpdf");
    file_put_contents("$filename.xml", $xml);
    $cmd = "xsltproc $filename.xml > $filename.html";
    exec($cmd, $output, $return);

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

$app->run();