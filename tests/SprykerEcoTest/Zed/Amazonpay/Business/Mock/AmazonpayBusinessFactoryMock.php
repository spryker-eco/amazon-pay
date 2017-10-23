<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock;

use SprykerEco\Zed\Amazonpay\Business\AmazonpayBusinessFactory;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\AdapterFactoryMock;

class AmazonpayBusinessFactoryMock extends AmazonpayBusinessFactory
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
     * @return \SprykerEcoTest\Zed\Amazonpay\Business\Mock\AmazonpayConfigMock
     */
    public function createAmazonpayConfig()
    {
        return new AmazonpayConfigMock($this->additionalConfig);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface
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
     * @return \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return new AmazonpayQueryContainer();
    }
}
