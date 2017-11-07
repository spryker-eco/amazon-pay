<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

interface QuoteUpdateFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteUpdaterCollection();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater();
}
