<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock;

use SprykerEco\Zed\AmazonPay\Business\AmazonPayBusinessFactory;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainer;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\AdapterFactoryMock;

class AmazonPayBusinessFactoryMock extends AmazonPayBusinessFactory
{
    /**
     * @var array
     */
    protected $additionalConfig;

    /**
     * @param array|null $additionalConfig
     */
    public function __construct($additionalConfig = null)
    {
        $this->additionalConfig = $additionalConfig;
    }

    /**
     * @return \SprykerEcoTest\Zed\AmazonPay\Business\Mock\AmazonPayConfigMock
     */
    public function createAmazonpayConfig()
    {
        return new AmazonPayConfigMock($this->additionalConfig);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface
     */
    public function createAdapterFactory()
    {
        return new AdapterFactoryMock(
            $this->createAmazonpayConfig(),
            $this->createConverterFactory(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return new AmazonPayQueryContainer();
    }
}
