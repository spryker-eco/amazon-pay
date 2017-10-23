<?php

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter;

use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AmazonpaySdkAdapterFactoryMock;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactory;

class AdapterFactoryMock extends AdapterFactory
{

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactoryInterface
     */
    protected function createSdkAdapterFactory()
    {
        return new AmazonpaySdkAdapterFactoryMock();
    }

}
