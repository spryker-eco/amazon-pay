<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use PayWithAmazon\IpnHandler;
use Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface;

class IpnRequestAdapter implements IpnRequestAdapterInterface
{

    /**
     * @var \PayWithAmazon\IpnHandler
     */
    protected $ipnHandler;

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    protected $ipnArrayConverter;

    /**
     * @param \PayWithAmazon\IpnHandler $ipnHandler
     * @param \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $ipnArrayConverter
     */
    public function __construct(
        IpnHandler $ipnHandler,
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
