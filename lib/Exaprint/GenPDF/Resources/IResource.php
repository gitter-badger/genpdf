<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbm
 * Date: 04/02/13
 * Time: 22:57
 * To change this template use File | Settings | File Templates.
 */

namespace Exaprint\GenPDF\Resources;

interface IResource {

    /**
     * @param $id
     * @return bool
     */
    public function fetchFromID($id);

    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getHeader();

    /**
     * @return string
     */
    public function getFooter();

    /**
     * @return mixed
     */
    public function getTemplateFilename();

    /**
     * @return string
     */
    public function getXml();
}