<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter\Sdk;

use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;

interface AmazonPaySdkAdapterFactoryInterface
{
    /**
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     *
     * @return \PayWithAmazon\Client
     */
    public function createAmazonPayClient(AmazonPayConfigInterface $config);

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \PayWithAmazon\IpnHandler
     */
    public function createAmazonPayIpnHandler(array $headers, $body);
}
