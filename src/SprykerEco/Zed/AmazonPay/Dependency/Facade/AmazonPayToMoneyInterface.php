<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Facade;

interface AmazonPayToMoneyInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value);
}
