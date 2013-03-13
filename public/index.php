<?php

require '../bootstrap.php';

$app->get("/", function(){
    echo _("hello");
});

$app->get("/:name/:id.xml", function ($name, $id) use ($app) {
    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);
    if ($resource && $resource->fetchFromID($id)) {
        echo $resource->getXml();
    }
});

$app->get("/:name/:id.pdf", function ($name, $id) use ($app) {

    deleteTemplateCache();

    $resource = \Exaprint\GenPDF\Resources\Factory::createFromName($name);

    if ($resource && $resource->fetchFromID($id)) {
        $app->view()->setData(
            $resource->getData()
        );

        $html = $app->view()->fetch(
            $resource->getTemplateFilename()
        );

        $filename = "/tmp/" . uniqid("genpdf");

        file_put_contents($filename . ".html", $html);

        $wkhtml = new \RBM\Wkhtmltopdf\Wkhtmltopdf();

        $wkhtml->setHeaderHtml("http://genpdf.exaprint.fr/static/assets/header.html");
        $wkhtml->setMarginTop(46);

        $wkhtml->setFooterHtml("http://genpdf.exaprint.fr/static/assets/footer.html");
        $wkhtml->setFooterSpacing(0);
        $wkhtml->setMarginBottom(40);

        $wkhtml->run("$filename.html", "$filename.pdf");
        $app->contentType("application/pdf");

        echo file_get_contents("$filename.pdf");

        unlink("$filename.html");
        unlink("$filename.pdf");
        return;
    }


    $app->status(404);

    echo "Impossible de trouver la ressource de type $name #$id";
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

$app->post("/labels/tnt-express-connect", function () use ($app) {
    $app->contentType("application/x-pdf");
    xsltProcess($app->request()->post("xml"));
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