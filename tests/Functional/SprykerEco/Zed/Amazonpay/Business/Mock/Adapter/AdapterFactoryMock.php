<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AmazonpaySdkAdapterFactoryMock;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactory;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface;

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
