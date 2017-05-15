<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock;

use SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade;

class AmazonpayFacadeMock extends AmazonpayFacade
{
    /**
     * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayBusinessFactory getFactory()
     * @return \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    protected function getFactory()
    {
        return new AmazonpayBusinessFactoryMock();
    }

}
