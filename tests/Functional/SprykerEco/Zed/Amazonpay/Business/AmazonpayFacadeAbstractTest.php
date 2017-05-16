<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Codeception\TestCase\Test;
use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock;

class AmazonpayFacadeAbstractTest extends Test
{

    /**
     * @return \Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock
     */
    protected function createFacade()
    {
        return new AmazonpayFacadeMock();
    }

}