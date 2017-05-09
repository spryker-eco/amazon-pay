<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface;

class OrderAuthFailedNotifyTransaction implements OrderTransactionInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\OrderNotificationSenderInterface
     */
    protected $orderFailedAuthNotificationSender;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\OrderMessageFactoryInterface
     */
    protected $orderMessageFactory;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\OrderNotificationSenderInterface $orderFailedAuthNotificationSender
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\OrderMessageFactoryInterface $orderMessageFactory
     */
    public function __construct(
        OrderNotificationSenderInterface $orderFailedAuthNotificationSender,
        OrderMessageFactoryInterface $orderMessageFactory
    ) {
        $this->orderFailedAuthNotificationSender = $orderFailedAuthNotificationSender;
        $this->orderMessageFactory = $orderMessageFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $orderTransfer)
    {
        $message = $this->orderMessageFactory->createFailedAuthMessage($orderTransfer);

        if ($orderTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()
        ) {
            $this->orderFailedAuthNotificationSender->notify($message);
        }

        return $orderTransfer;
    }

}
