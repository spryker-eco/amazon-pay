<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use PayWithAmazon\IpnHandlerInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterInterface;

class IpnRequestAdapter implements IpnRequestAdapterInterface
{
    /**
     * @var \PayWithAmazon\IpnHandlerInterface
     */
    protected $ipnHandler;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterInterface
     */
    protected $ipnArrayConverter;

    /**
     * @param \PayWithAmazon\IpnHandlerInterface $ipnHandler
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterInterface $ipnArrayConverter
     */
    public function __construct(
        IpnHandlerInterface $ipnHandler,
        IpnConverterInterface $ipnArrayConverter
    ) {
        $this->ipnHandler = $ipnHandler;
        $this->ipnArrayConverter = $ipnArrayConverter;
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer
     */
    public function getIpnRequest()
    {
        return $this->ipnArrayConverter->convert(
            $this->ipnHandler->toArray()
        );
    }
}
