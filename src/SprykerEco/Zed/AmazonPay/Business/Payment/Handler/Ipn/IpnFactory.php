<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLogger;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

class IpnFactory implements IpnFactoryInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface
     */
    protected $encodingService;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface $amazonpayToUtilEncoding
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        AmazonPayToOmsInterface $omsFacade,
        AmazonPayQueryContainerInterface $amazonpayQueryContainer,
        AmazonPayToUtilEncodingInterface $amazonpayToUtilEncoding,
        RefundOrderInterface $refundOrderModel
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->encodingService = $amazonpayToUtilEncoding;
        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface
     */
    public function createIpnRequestLogger()
    {
        return new IpnRequestLogger($this->encodingService);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestFactoryInterface
     */
    public function createIpnRequestFactory()
    {
        return new IpnRequestFactory(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->createIpnRequestLogger(),
            $this->refundOrderModel
        );
    }
}
