<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock;

use SprykerEco\Zed\AmazonPay\Business\AmazonPayFacade;

class AmazonPayFacadeMock extends AmazonPayFacade
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
     * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayBusinessFactory getFactory()
     *
     * @return \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    public function getFactory()
    {
        return new AmazonPayBusinessFactoryMock($this->additionalConfig);
    }
}
