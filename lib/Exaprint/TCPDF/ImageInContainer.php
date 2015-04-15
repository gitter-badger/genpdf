<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/02/2014
 * Time: 16:50
 */

namespace Exaprint\TCPDF;


class ImageInContainer implements Element
{

    public $imageFile;

    /**
     * @var Dimensions
     */
    public $imageDimensions;

    /**
     * @var Position
     */
    public $containerPosition;

    /**
     * @var Dimensions
     */
    public $containerDimensions;

    /**
     * @var bool
     */
    public $autoRotate;

    function __construct(
        $imageFile,
        Dimensions $imageDimensions,
        Dimensions $containerDimensions,
        Position $containerPosition,
        $autoRotate = true
    )
    {
        $this->containerDimensions = $containerDimensions;
        $this->autoRotate          = $autoRotate;
        $this->containerPosition   = $containerPosition;
        $this->imageDimensions     = $imageDimensions;
        $this->imageFile           = $imageFile;
    }


    function draw(\TCPDF $pdf)
    {
        $pdf->setAlpha(1);
        $imageRatio      = $this->imageDimensions->ratio();
        $containerRatio  = $this->containerDimensions->ratio();
        $containerX      = $this->containerPosition->x;
        $containerY      = $this->containerPosition->y;
        $containerWidth  = $this->containerDimensions->width;
        $containerHeight = $this->containerDimensions->height;

        $rotate = (($imageRatio > 1 && $containerRatio < 1) || ($imageRatio < 1 && $containerRatio > 1)) && $this->autoRotate;

        $image       = new Image();
        $image->file = $this->imageFile;

        if ($rotate) {

            $pdf->StartTransform();
            $pdf->Rotate(90,
                $containerX + $containerWidth / 2,
                $containerY + $containerHeight / 2
            );

            if ($containerWidth * $imageRatio <= $containerHeight) {
                $image->height = $containerWidth;
                $image->width  = $containerWidth * $imageRatio;
            } else {
                $image->height = $containerHeight / $imageRatio;
                $image->width  = $containerHeight;
            }

        } else {
            if ($containerWidth / $imageRatio <= $containerHeight) {
                $image->width  = $containerWidth;
                $image->height = $containerWidth / $imageRatio;
            } else {
                $image->height = $containerHeight;
                $image->width  = $containerHeight * $imageRatio;
            }

        }


        $image->x = $containerX - ($image->width - $containerWidth) / 2;
        $image->y = $containerY + ($containerHeight - $image->height) / 2;

        $image->draw($pdf);

        if ($rotate) $pdf->StopTransform();

        $pdf->Rect(
            $containerX,
            $containerY,
            $containerWidth,
            $containerHeight
        );

    }
} 