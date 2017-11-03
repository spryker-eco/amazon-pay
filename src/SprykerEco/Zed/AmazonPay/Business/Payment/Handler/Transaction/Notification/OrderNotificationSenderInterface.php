<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification;

interface OrderNotificationSenderInterface
{
    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification\NotificationMessageInterface $message
     *
     * @return void
     */
    public function notify(NotificationMessageInterface $message);
}
