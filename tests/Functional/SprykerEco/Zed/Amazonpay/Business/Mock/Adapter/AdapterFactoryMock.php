<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AmazonpaySdkAdapterFactoryMock;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
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
