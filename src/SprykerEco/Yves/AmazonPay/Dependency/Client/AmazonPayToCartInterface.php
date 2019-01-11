<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Dependency\Client;

interface AmazonPayToCartInterface
{
    /**
     * @return void
     */
    public function clearQuote();
}
