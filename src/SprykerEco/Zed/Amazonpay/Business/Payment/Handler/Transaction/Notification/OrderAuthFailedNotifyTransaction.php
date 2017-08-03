<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface;

class OrderAuthFailedNotifyTransaction implements AmazonpayTransactionInterface
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
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $message = $this->orderMessageFactory->createFailedAuthMessage($amazonpayCallTransfer);

        if ($amazonpayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()
        ) {
            $this->orderFailedAuthNotificationSender->notify($message);
        }

        return $amazonpayCallTransfer;
    }

}
