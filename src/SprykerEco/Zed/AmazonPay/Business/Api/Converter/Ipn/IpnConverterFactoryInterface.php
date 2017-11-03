<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn;

interface IpnConverterFactoryInterface
{
    /**
     * @param array $request
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnRequestConverter(array $request);
}
