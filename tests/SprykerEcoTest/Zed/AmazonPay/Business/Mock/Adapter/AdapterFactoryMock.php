<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter;

use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactory;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AmazonpaySdkAdapterFactoryMock;

class AdapterFactoryMock extends AdapterFactory
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactoryInterface
     */
    protected function createSdkAdapterFactory()
    {
        return new AmazonpaySdkAdapterFactoryMock();
    }
}
