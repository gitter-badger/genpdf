<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/02/2014
 * Time: 11:16
 */

namespace Exaprint\TCPDF;


class Rect implements Element
{
    /*	 *   <li>S or D: Stroke the path.</li>
	 *   <li>s or d: Close and stroke the path.</li>
	 *   <li>f or F: Fill the path, using the nonzero winding number rule to determine the region to fill.</li>
	 *   <li>f* or F*: Fill the path, using the even-odd rule to determine the region to fill.</li>
	 *   <li>B or FD or DF: Fill and then stroke the path, using the nonzero winding number rule to determine the region to fill.</li>
	 *   <li>B* or F*D or DF*: Fill and then stroke the path, using the even-odd rule to determine the region to fill.</li>
	 *   <li>b or fd or df: Close, fill, and then stroke the path, using the nonzero winding number rule to determine the region to fill.</li>
	 *   <li>b or f*d or df*: Close, fill, and then stroke the path, using the even-odd rule to determine the region to fill.</li>
	 *   <li>CNZ: Clipping mode using the even-odd rule to determine which regions lie inside the clipping path.</li>
	 *   <li>CEO: Clipping mode using the nonzero winding number rule to determine which regions lie inside the clipping path</li>
	 *   <li>n: End the path object without filling or stroking it.</li>

    */

    const STYLE_STROKE           = 'S';
    const STYLE_CLOSE_STROKE     = 's';
    const STYLE_FILL             = 'F';
    const STYLE_FILL_THEN_STROKE = 'B';

    /** @var  Position */
    public $position;

    /** @var  Dimensions */
    public $dimensions;

    /** @var  FillColor */
    public $fillColor;

    public $style = '';

    public function draw(\TCPDF $pdf)
    {
        if ($this->fillColor)
            $this->fillColor->apply($pdf);

        $pdf->Rect(
            $this->position->x,
            $this->position->y,
            $this->dimensions->width,
            $this->dimensions->height,
            $this->style
        );

        if($this->fillColor)
            $this->fillColor->revert($pdf);
    }

}