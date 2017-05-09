<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

interface OrderNotificationSenderInterface
{

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\AbstractNotificationMessage $message
     *
     * @return void
     */
    public function notify(AbstractNotificationMessage $message);

}
