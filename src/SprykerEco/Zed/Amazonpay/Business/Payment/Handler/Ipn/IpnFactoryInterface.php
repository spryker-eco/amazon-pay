<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

interface IpnFactoryInterface
{

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLogger
     */
    public function createIpnRequestLogger();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestFactoryInterface
     */
    public function createIpnRequestFactory();

}
