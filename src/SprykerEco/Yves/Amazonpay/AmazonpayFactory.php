<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class AmazonpayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToCheckoutInterface
     */
    public function getCheckoutClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    public function createAmazonpayConfig()
    {
        return new AmazonpayConfig();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToShipmentBridgeInterface
     */
    public function getShipmentClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToCalculationInterface
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToCustomerInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_CUSTOMER);
    }
}
