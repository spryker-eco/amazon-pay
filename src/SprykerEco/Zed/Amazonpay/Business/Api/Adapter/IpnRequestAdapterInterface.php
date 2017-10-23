<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

interface IpnRequestAdapterInterface
{
    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getIpnRequest();
}
