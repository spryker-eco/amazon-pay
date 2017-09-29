<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock;

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
