<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLogger;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

class IpnFactory implements IpnFactoryInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface
     */
    protected $encodingService;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface $amazonpayToUtilEncoding
     * @param \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        AmazonpayToOmsInterface $omsFacade,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        AmazonpayToUtilEncodingInterface $amazonpayToUtilEncoding,
        RefundOrderInterface $refundOrderModel
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->encodingService = $amazonpayToUtilEncoding;
        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface
     */
    public function createIpnRequestLogger()
    {
        return new IpnRequestLogger($this->encodingService);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestFactoryInterface
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
