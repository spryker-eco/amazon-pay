<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\AmazonPay;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\AmazonPay\Zed\AmazonPayStub;

class AmazonPayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\AmazonPay\Zed\AmazonPayStubInterface
     */
    public function createZedStub()
    {
        return new AmazonPayStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
