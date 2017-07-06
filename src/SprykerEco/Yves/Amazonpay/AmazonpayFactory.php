<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay;

use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use Spryker\Yves\Kernel\AbstractFactory;

class AmazonpayFactory extends AbstractFactory implements AmazonpayFactoryInterface
{

    /**
     * @return \Spryker\Client\Quote\QuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\Checkout\CheckoutClientInterface
     */
    public function getCheckoutClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \SprykerEco\Shared\Amazonpay\AmazonpayConfig
     */
    public function getConfig()
    {
        return new AmazonpayConfig();
    }

    /**
     * @return \Spryker\Client\Shipment\ShipmentClientInterface
     */
    public function getShipmentClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    public function createProductBundleGrouper()
    {
        return new ProductBundleGrouper();
    }

}
