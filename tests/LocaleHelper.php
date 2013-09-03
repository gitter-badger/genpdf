<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lucien
 * Date: 02/09/13
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

require '../vendor/autoload.php';

class LocaleHelper extends PHPUnit_Framework_TestCase {

    public function testFormatMoneyFR() {

        \Locale\Helper::$current = 'fr_FR';

        $format = \Locale\Helper::formatMoney(1000.654);

        $this->assertEquals('1 000,65 <span class="currency">€</span>', $format);
    }

    public function testFormatMoneyEN() {

        \Locale\Helper::$current = 'en_GB';

        $format = \Locale\Helper::formatMoney(1000.654);

        $this->assertEquals('<span class="currency">£</span>1,000.65', $format);
    }

    public function testFormatMoneyNULL() {

        \Locale\Helper::$current = 'en_GB';

        $format = \Locale\Helper::formatMoney(NULL);

        $this->assertEquals('<span class="currency">£</span>0.00', $format);
    }

    public function testFormatNumberFR() {

        \Locale\Helper::$current = 'fr_FR';

        $format = \Locale\Helper::formatNumber(1000.65432);

        $this->assertEquals('1 000,65', $format);
    }

    public function testFormatNumberEN() {

        \Locale\Helper::$current = 'en_GB';

        $format = \Locale\Helper::formatNumber(1000.65432);

        $this->assertEquals('1,000.65', $format);
    }

    public function testFormatNumberNULL() {

        \Locale\Helper::$current = 'en_GB';

        $format = \Locale\Helper::formatNumber(NULL);

        $this->assertEquals('0.00', $format);
    }

}
