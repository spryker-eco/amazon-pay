<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Authorize;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnAbstractTransferRequestHandler;

abstract class IpnAbstractPaymentAuthorizeHandler extends IpnAbstractTransferRequestHandler
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function retrievePaymentEntity(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        return $this->queryContainer
            ->queryPaymentByAuthorizationReferenceId(
                $paymentRequestTransfer->getAuthorizationDetails()->getAuthorizationReferenceId()
            )
            ->findOne();
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_UPDATE_AUTH_STATUS;
    }
}
