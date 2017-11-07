<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\Sdk\AmazonPaySdkAdapterFactory;

class AmazonPaySdkAdapterFactoryMock extends AmazonPaySdkAdapterFactory
{
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

        return new ClientMock($aConfig);
    }
}
