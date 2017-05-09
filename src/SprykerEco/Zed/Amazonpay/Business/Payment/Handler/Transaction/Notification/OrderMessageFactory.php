<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

use Generated\Shared\Transfer\OrderTransfer;

class OrderMessageFactory implements OrderMessageFactoryInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\AbstractNotificationMessage
     */
    public function createFailedAuthMessage(OrderTransfer $orderTransfer)
    {
        if ($orderTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getIsSuspended()
        ) {
            return new OrderAuthFailedSoftDeclineMessage($orderTransfer);
        } elseif ($orderTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getIsDeclined()
        ) {
            return new OrderAuthFailedHardDeclineMessage($orderTransfer);
        }
    }

}
