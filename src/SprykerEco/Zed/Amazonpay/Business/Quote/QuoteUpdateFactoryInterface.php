<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

interface QuoteUpdateFactoryInterface
{

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteDataInitializer();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater();

}
