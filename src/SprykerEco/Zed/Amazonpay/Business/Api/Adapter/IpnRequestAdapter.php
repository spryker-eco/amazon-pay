<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use PayWithAmazon\IpnHandlerInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface;

class IpnRequestAdapter implements IpnRequestAdapterInterface
{
    /**
     * @var \PayWithAmazon\IpnHandlerInterface
     */
    protected $ipnHandler;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    protected $ipnArrayConverter;

    /**
     * @param \PayWithAmazon\IpnHandlerInterface $ipnHandler
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $ipnArrayConverter
     */
    public function __construct(
        IpnHandlerInterface $ipnHandler,
        ArrayConverterInterface $ipnArrayConverter
    ) {
        $this->ipnHandler = $ipnHandler;
        $this->ipnArrayConverter = $ipnArrayConverter;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getIpnRequest()
    {
        return $this->ipnArrayConverter->convert(
            $this->ipnHandler->toArray()
        );
    }
}
