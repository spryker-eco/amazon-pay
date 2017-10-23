<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

class OrderNotificationFactory implements OrderNotificationFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createOrderAuthFailedTransaction()
    {
        return new OrderAuthFailedNotifyTransaction(
            $this->createFailedAuthNotificationSender(),
            $this->createOrderMessageFactory()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\OrderNotificationSenderInterface
     */
    protected function createFailedAuthNotificationSender()
    {
        return new OrderFailedAuthNotificationSender();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\OrderMessageFactoryInterface
     */
    protected function createOrderMessageFactory()
    {
        return new OrderMessageFactory();
    }
}
