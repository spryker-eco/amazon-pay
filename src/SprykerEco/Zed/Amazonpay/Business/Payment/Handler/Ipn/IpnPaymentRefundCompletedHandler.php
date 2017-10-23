<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

class IpnPaymentRefundCompletedHandler extends IpnAbstractPaymentRefundHandler
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     * @param \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        AmazonpayToOmsInterface $omsFacade,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger,
        RefundOrderInterface $refundOrderModel
    ) {

        parent::__construct($omsFacade, $amazonpayQueryContainer, $ipnRequestLogger);

        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonpayConstants::OMS_STATUS_REFUND_COMPLETED;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayIpnRequestTransfer
     *
     * @return void
     */
    public function handle(AbstractTransfer $amazonpayIpnRequestTransfer)
    {
        parent::handle($amazonpayIpnRequestTransfer);

        $paymentEntity = $this->retrievePaymentEntity($amazonpayIpnRequestTransfer);

        if ($paymentEntity === null) {
            return;
        }

        $this->refundOrderModel->refundPayment($paymentEntity);
    }

}
