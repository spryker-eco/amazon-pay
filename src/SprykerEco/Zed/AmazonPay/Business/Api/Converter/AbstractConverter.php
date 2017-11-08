<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayPriceTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

abstract class AbstractConverter
{
    const STATUS_DECLINED = 'Declined';
    const STATUS_PENDING = 'Pending';
    const STATUS_OPEN = 'Open';
    const STATUS_CLOSED = 'Closed';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_SUSPENDED = 'Suspended';
    const STATUS_CANCELLED = 'Canceled';

    const FIELD_LAST_UPDATE_TIMESTAMP = 'LastUpdateTimestamp';
    const FIELD_REASON_CODE = 'ReasonCode';
    const FIELD_STATE = 'State';
    const FIELD_AMOUNT = 'Amount';
    const FIELD_CURRENCY_CODE = 'CurrencyCode';

    const REASON_CODE_EXPIRED_UNUSED = 'ExpiredUnused';
    const REASON_CODE_SELLER_CLOSED = 'SellerClosed';
    const REASON_CODE_PAYMENT_METHOD_INVALID = 'InvalidPaymentMethod';
    const REASON_CODE_AMAZON_CLOSED = 'AmazonClosed';
    const REASON_CODE_TRANSACTION_TIMED_OUT = 'TransactionTimedOut';

    /**
     * @var array
     */
    protected $fieldToTransferMap = [
        self::FIELD_STATE => AmazonpayStatusTransfer::STATE,
        self::FIELD_REASON_CODE => AmazonpayStatusTransfer::REASON_CODE,
        self::FIELD_LAST_UPDATE_TIMESTAMP => AmazonpayStatusTransfer::LAST_UPDATE_TIMESTAMP
    ];

    /**
     * @var array
     */
    protected $statusMap = [
        self::STATUS_DECLINED => AmazonPayConfig::STATUS_DECLINED,
        self::STATUS_SUSPENDED => AmazonPayConfig::STATUS_SUSPENDED,
        self::STATUS_PENDING => AmazonPayConfig::STATUS_PENDING,
        self::STATUS_OPEN => AmazonPayConfig::STATUS_OPEN,
        self::STATUS_CLOSED => AmazonPayConfig::STATUS_CLOSED,
        self::STATUS_COMPLETED => AmazonPayConfig::STATUS_COMPLETED,
        self::STATUS_CANCELLED => AmazonPayConfig::STATUS_CANCELLED,
    ];
    /**
     * @var array
     */
    protected $reasonToStatusMap = [
        self::REASON_CODE_AMAZON_CLOSED => AmazonPayConfig::STATUS_AMAZON_CLOSED,
        self::REASON_CODE_PAYMENT_METHOD_INVALID => AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID,
        self::REASON_CODE_TRANSACTION_TIMED_OUT => AmazonPayConfig::STATUS_TRANSACTION_TIMED_OUT,
        self::REASON_CODE_SELLER_CLOSED => AmazonPayConfig::STATUS_EXPIRED,
        self::REASON_CODE_EXPIRED_UNUSED => AmazonPayConfig::STATUS_EXPIRED,
    ];

    /**
     * @param array $priceData
     *
     * @return \Generated\Shared\Transfer\AmazonpayPriceTransfer
     */
    protected function convertPriceToTransfer(array $priceData)
    {
        $priceTransfer = new AmazonpayPriceTransfer();

        $priceTransfer->setAmount($priceData[static::FIELD_AMOUNT]);
        $priceTransfer->setCurrencyCode($priceData[static::FIELD_CURRENCY_CODE]);

        return $priceTransfer;
    }

    /**
     * @param array $statusData
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function convertStatusToTransfer(array $statusData)
    {
        $statusTransfer = new AmazonpayStatusTransfer();

        $mappedStatusData = $this->mapStatusToTransferProperties($statusData);
        $statusTransfer->fromArray($mappedStatusData, true);

        $statusName = $this->getStatusName($statusData);
        if ($statusName !== null) {
            $statusTransfer->setState($statusName);
        }

        $statusNameByReasonCode = $this->getStatusNameByReasonCode($statusData);
        if ($statusNameByReasonCode !== null) {
            $statusTransfer->setState($statusNameByReasonCode);
        }

        return $statusTransfer;
    }

    /**
     * @param array $statusData
     *
     * @return array
     */
    protected function mapStatusToTransferProperties(array $statusData)
    {
        $result = [];
        foreach ($this->fieldToTransferMap as $statusField=>$propertyName) {
            $result[$propertyName] = $statusData[$statusField] ?? null;
        }

        return $result;
    }

    /**
     * @param array $statusData
     *
     * @return string|null
     */
    protected function getStatusName(array $statusData)
    {
        if (isset(
            $statusData[static::FIELD_STATE],
            $this->statusMap[$statusData[static::FIELD_STATE]]
        )) {
            return $this->statusMap[$statusData[static::FIELD_STATE]];
        }

        return null;
    }

    /**
     * @param array $statusData
     *
     * @return string|null
     */
    protected function getStatusNameByReasonCode(array $statusData)
    {
        if (!empty($statusData[static::FIELD_REASON_CODE])
            && isset($this->reasonToStatusMap[$statusData[static::FIELD_REASON_CODE]]
        )) {
            return $this->reasonToStatusMap[$statusData[static::FIELD_REASON_CODE]];
        }

        return null;
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
