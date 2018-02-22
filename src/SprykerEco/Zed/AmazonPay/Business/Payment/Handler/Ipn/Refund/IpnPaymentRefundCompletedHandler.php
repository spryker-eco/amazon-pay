<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Refund;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

class IpnPaymentRefundCompletedHandler extends IpnAbstractPaymentRefundHandler
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface $refundOrderModel
     * @param AmazonPayConfig $config
     */
    public function __construct(
        AmazonPayToOmsInterface $omsFacade,
        AmazonPayQueryContainerInterface $queryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger,
        AmazonPayConfig $config,
        RefundOrderInterface $refundOrderModel
    ) {

        parent::__construct($omsFacade, $queryContainer, $ipnRequestLogger, $config);

        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @return string
     */
    protected function getStatusName()
    {
        return AmazonPayConfig::STATUS_COMPLETED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return void
     */
    public function handle(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        parent::handle($paymentRequestTransfer);

        $paymentEntity = $this->retrievePaymentEntity($paymentRequestTransfer);

        if ($paymentEntity !== null) {
            $this->refundOrderModel->refundPayment($paymentEntity);
        }
    }
}
