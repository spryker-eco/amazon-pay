<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock;

use SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade;

class AmazonpayFacadeMock extends AmazonpayFacade
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
     * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayBusinessFactory getFactory()
     *
     * @return \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    public function getFactory()
    {
        return new AmazonpayBusinessFactoryMock($this->additionalConfig);
    }
}
