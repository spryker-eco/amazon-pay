<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayPriceTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

abstract class AbstractConverter
{
    const STATUS_DECLINED = 'Declined';
    const STATUS_PENDING = 'Pending';
    const STATUS_OPEN = 'Open';
    const STATUS_CLOSED = 'Closed';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_SUSPENDED = 'Suspended';
    const STATUS_CANCELLED = 'Canceled';
    const LAST_UPDATE_TIMESTAMP = 'LastUpdateTimestamp';
    const REASON_CODE = 'ReasonCode';
    const STATE = 'State';

    /**
     * @param array $priceData
     *
     * @return \Generated\Shared\Transfer\AmazonpayPriceTransfer
     */
    protected function convertPriceToTransfer(array $priceData)
    {
        $priceTransfer = new AmazonpayPriceTransfer();

        $priceTransfer->setAmount($priceData['Amount']);
        $priceTransfer->setCurrencyCode($priceData['CurrencyCode']);

        return $priceTransfer;
    }

    /**
     * @param array $statusData
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function convertStatusToTransfer(array $statusData)
    {
        $status = new AmazonpayStatusTransfer();

        if (!empty($statusData[self::LAST_UPDATE_TIMESTAMP])) {
            $status->setLastUpdateTimestamp($statusData[self::LAST_UPDATE_TIMESTAMP]);
        }

        $status->setState($statusData[self::STATE]);

        if (!empty($statusData[self::REASON_CODE])) {
            $status->setReasonCode($statusData[self::REASON_CODE]);
            $status->setIsReauthorizable(
                $statusData[self::REASON_CODE] === AmazonpayConfig::REASON_CODE_SELLER_CLOSED
                || $statusData[self::REASON_CODE] === AmazonpayConfig::REASON_CODE_EXPIRED_UNUSED
            );

            $status->setIsPaymentMethodInvalid(
                $statusData[self::REASON_CODE] === AmazonpayConfig::REASON_CODE_PAYMENT_METHOD_INVALID
            );

            $status->setIsClosedByAmazon(
                $statusData[self::REASON_CODE] === AmazonpayConfig::REASON_CODE_AMAZON_CLOSED
            );

            $status->setIsTransactionTimedOut(
                $statusData[self::REASON_CODE] === AmazonpayConfig::REASON_CODE_TRANSACTION_TIMED_OUT
            );
        }

        if ($statusData[self::STATE] === static::STATUS_DECLINED) {
            $status->setIsSuspended($status->getIsPaymentMethodInvalid());
            $status->setIsDeclined(true);
        }

        if ($statusData[self::STATE] === static::STATUS_SUSPENDED) {
            $status->setIsSuspended(true);
            $status->setIsDeclined(true);
        }

        $status->setIsPending(
            $statusData[self::STATE] === static::STATUS_PENDING
        );

        $status->setIsOpen(
            $statusData[self::STATE] === static::STATUS_OPEN
        );

        $status->setIsClosed(
            $statusData[self::STATE] === static::STATUS_CLOSED
        );

        $status->setIsCompleted(
            $statusData[self::STATE] === static::STATUS_COMPLETED
        );

        $status->setIsCancelled(
            $statusData[self::STATE] === static::STATUS_CANCELLED
        );

        return $status;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\CustomerTransfer|\Generated\Shared\Transfer\AddressTransfer $transfer
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Generated\Shared\Transfer\AddressTransfer
     */
    protected function updateNameData(TransferInterface $transfer, $name)
    {
        $names = explode(' ', $name, 2);

        if (count($names) === 2) {
            $transfer->setFirstName($names[0]);
            $transfer->setLastName($names[1]);
        } else {
            $transfer->setFirstName($name);
            $transfer->setLastName($name);
        }

        return $transfer;
    }
}
