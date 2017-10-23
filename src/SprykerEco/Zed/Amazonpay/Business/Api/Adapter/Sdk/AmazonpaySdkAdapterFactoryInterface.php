<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter\Sdk;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;

interface AmazonpaySdkAdapterFactoryInterface
{
    /**
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     *
     * @return \PayWithAmazon\Client
     */
    public function createAmazonpayClient(AmazonpayConfigInterface $config);

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \PayWithAmazon\IpnHandler
     */
    public function createAmazonpayIpnHandler(array $headers, $body);
}
