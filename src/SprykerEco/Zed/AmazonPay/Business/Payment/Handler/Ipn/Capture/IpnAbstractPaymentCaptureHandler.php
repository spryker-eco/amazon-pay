<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Capture;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnAbstractTransferRequestHandler;

abstract class IpnAbstractPaymentCaptureHandler extends IpnAbstractTransferRequestHandler
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        return $this->queryContainer
            ->queryPaymentByCaptureReferenceId(
                $paymentRequestTransfer->getCaptureDetails()->getCaptureReferenceId()
            )
            ->findOne();
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_UPDATE_CAPTURE_STATUS;
    }
}
