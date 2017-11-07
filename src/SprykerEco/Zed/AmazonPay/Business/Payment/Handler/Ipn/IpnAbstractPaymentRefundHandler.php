<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

abstract class IpnAbstractPaymentRefundHandler extends IpnAbstractTransferRequestHandler
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        return $this->amazonPayQueryContainer
            ->queryPaymentByRefundReferenceId(
                $paymentRequestTransfer->getRefundDetails()->getRefundReferenceId()
            )
            ->findOne();
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_UPDATE_REFUND_STATUS;
    }
}
