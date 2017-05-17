<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\AdapterFactoryMock;
use SprykerEco\Zed\Amazonpay\Business\AmazonpayBusinessFactory;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer;

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
     * @return AmazonpayConfigMock
     */
    public function getConfig()
    {
        return new AmazonpayConfigMock($this->additionalConfig);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface
     */
    public function createAdapterFactory()
    {
        return new AdapterFactoryMock(
            $this->getConfig(),
            $this->createConverterFactory(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer
     */
    protected function getQueryContainer()
    {
        return new AmazonpayQueryContainer();
    }

}
