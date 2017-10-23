<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class OrderMessageFactory implements OrderMessageFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\NotificationMessageInterface|null
     */
    public function getFailedAuthMessage(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if ($amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getIsSuspended()
        ) {
            return $this->createOrderAuthFailedSoftDeclineMessage($amazonpayCallTransfer);
        }

        if ($amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getIsDeclined()
        ) {
            return $this->createOrderAuthFailedHardDeclineMessage($amazonpayCallTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\NotificationMessageInterface
     */
    protected function createOrderAuthFailedSoftDeclineMessage(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return new OrderAuthFailedSoftDeclineMessage($amazonpayCallTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\NotificationMessageInterface
     */
    protected function createOrderAuthFailedHardDeclineMessage(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return new OrderAuthFailedHardDeclineMessage($amazonpayCallTransfer);
    }
}
