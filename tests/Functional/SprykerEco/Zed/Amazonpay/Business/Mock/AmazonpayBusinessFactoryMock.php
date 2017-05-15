<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\AdapterFactoryMock;
use SprykerEco\Zed\Amazonpay\Business\AmazonpayBusinessFactory;

class AmazonpayBusinessFactoryMock extends AmazonpayBusinessFactory
{
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

}


