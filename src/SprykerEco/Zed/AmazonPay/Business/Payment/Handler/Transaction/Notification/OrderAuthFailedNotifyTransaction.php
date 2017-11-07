<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface;

class OrderAuthFailedNotifyTransaction implements AmazonpayTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification\OrderNotificationSenderInterface
     */
    protected $orderFailedAuthNotificationSender;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification\OrderMessageBuilderInterface
     */
    protected $orderMessageFactory;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification\OrderNotificationSenderInterface $orderFailedAuthNotificationSender
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Notification\OrderMessageBuilderInterface $orderMessageFactory
     */
    public function __construct(
        OrderNotificationSenderInterface $orderFailedAuthNotificationSender,
        OrderMessageBuilderInterface $orderMessageFactory
    ) {
        $this->orderFailedAuthNotificationSender = $orderFailedAuthNotificationSender;
        $this->orderMessageFactory = $orderMessageFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $message = $this->orderMessageFactory->getFailedAuthMessage($amazonPayCallTransfer);

        if ($amazonPayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()
        ) {
            $this->orderFailedAuthNotificationSender->notify($message);
        }

        return $amazonPayCallTransfer;
    }
}
