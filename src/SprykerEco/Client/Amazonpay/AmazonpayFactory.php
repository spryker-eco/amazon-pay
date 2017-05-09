<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Amazonpay;

use Spryker\Client\Amazonpay\Zed\AmazonpayStub;
use Spryker\Client\Kernel\AbstractFactory;

class AmazonpayFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Amazonpay\Zed\AmazonpayStubInterface
     */
    public function createZedStub()
    {
        return new AmazonpayStub($this->getProvidedDependency(AmazonpayDependencyProvider::CLIENT_ZED_REQUEST));
    }

}
