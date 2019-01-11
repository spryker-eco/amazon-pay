<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class AmazonPayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCartInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCheckoutInterface
     */
    public function getCheckoutClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    public function createAmazonPayConfig()
    {
        return new AmazonPayConfig();
    }

    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToShipmentInterface
     */
    public function getShipmentClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCalculationInterface
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCustomerInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToGlossaryStorageInterface
     */
    public function getGlossaryStorageClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
