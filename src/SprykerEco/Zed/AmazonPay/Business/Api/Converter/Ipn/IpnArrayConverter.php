<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn;

class IpnArrayConverter implements IpnConverterInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface
     */
    protected $ipnConverterFactory;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface $ipnConverterFactory
     */
    public function __construct(IpnConverterFactoryInterface $ipnConverterFactory)
    {
        $this->ipnConverterFactory = $ipnConverterFactory;
    }

    /**
     * @param array $request
     * @param string $body
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function convert(array $request, $body)
    {
        return $this->ipnConverterFactory
            ->getIpnRequestConverter($request)
            ->convert($request, $body);
    }
}
