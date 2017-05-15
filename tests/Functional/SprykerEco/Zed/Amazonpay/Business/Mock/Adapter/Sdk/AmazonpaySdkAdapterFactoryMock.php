<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactory;

class AmazonpaySdkAdapterFactoryMock extends AmazonpaySdkAdapterFactory
{
    /**
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     *
     * @return \PayWithAmazon\ClientInterface
     */
    public function createAmazonpayClient(AmazonpayConfigInterface $config)
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