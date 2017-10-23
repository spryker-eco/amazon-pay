<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn;

use SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter;

/**
 * Class IpnArrayConverter
 * Converts request taken from IpnHandler to the Transfer Object
 */
class IpnArrayConverter extends AbstractArrayConverter
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface
     */
    protected $ipnConverterFactory;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface $ipnConverterFactory
     */
    public function __construct(IpnConverterFactoryInterface $ipnConverterFactory)
    {
        $this->ipnConverterFactory = $ipnConverterFactory;
    }

    /**
     * @param array $ipnRequest
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function convert(array $ipnRequest)
    {
        return $this->ipnConverterFactory->createIpnRequestConverter($ipnRequest)->convert($ipnRequest);
    }

}
