<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter\Sdk;

use PayWithAmazon\Client;
use PayWithAmazon\IpnHandler;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;

class AmazonPaySdkAdapterFactory implements AmazonPaySdkAdapterFactoryInterface
{
    public const MERCHANT_ID = 'merchant_id';
    public const PLATFORM_ID = 'platform_id';
    public const ACCESS_KEY = 'access_key';
    public const SECRET_KEY = 'secret_key';
    public const CLIENT_ID = 'client_id';
    public const REGION = 'region';
    public const CURRENCY_CODE = 'currency_code';
    public const SANDBOX = 'sandbox';

    /**
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     *
     * @return \PayWithAmazon\ClientInterface
     */
    public function createAmazonPayClient(AmazonPayConfigInterface $config)
    {
        $aConfig = [
            static::MERCHANT_ID => $config->getSellerId(),
            static::PLATFORM_ID => $config->getSellerId(),
            static::ACCESS_KEY => $config->getAccessKeyId(),
            static::SECRET_KEY => $config->getSecretAccessKey(),
            static::CLIENT_ID => $config->getClientId(),
            static::REGION => $config->getRegion(),
            static::CURRENCY_CODE => $config->getCurrencyIsoCode(),
            static::SANDBOX => $config->isSandbox(),
        ];

        return new Client($aConfig);
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \PayWithAmazon\IpnHandlerInterface
     */
    public function createAmazonPayIpnHandler(array $headers, $body)
    {
        return new IpnHandler($headers, $body);
    }
}
