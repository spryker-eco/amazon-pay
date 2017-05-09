<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

interface QuoteUpdateFactoryInterface
{

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteDataInitializer();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteInitializer();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater();

}
