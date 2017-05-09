<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay;

interface AmazonpayFactoryInterface
{

    /**
     * @return \Spryker\Client\Quote\QuoteClientInterface
     */
    public function getQuoteClient();

    /**
     * @return \Spryker\Client\Checkout\CheckoutClientInterface
     */
    public function getCheckoutClient();

    /**
     * @return \Spryker\Shared\Amazonpay\AmazonpayConfig
     */
    public function getConfig();

    /**
     * @return \Spryker\Client\Shipment\ShipmentClientInterface
     */
    public function getShipmentClient();

    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    public function getCalculationClient();

}
