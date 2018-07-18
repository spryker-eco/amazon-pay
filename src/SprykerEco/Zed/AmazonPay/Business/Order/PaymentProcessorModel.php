<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Order;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

class PaymentProcessorModel implements PaymentProcessorInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface
     */
    protected $converter;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface $converter
     */
    public function __construct(
        AmazonPayQueryContainerInterface $queryContainer,
        AmazonPayTransferToEntityConverterInterface $converter
    ) {
        $this->queryContainer = $queryContainer;
        $this->converter = $converter;
    }

    /**
     * @param string $orderReferenceId
     * @param string $status
     *
     * @return void
     */
    public function updateStatus($orderReferenceId, $status)
    {
        $payments = $this->getPayments($orderReferenceId);
        foreach ($payments as $payment) {
            $payment->setStatus($status);
            $payment->save();
        }
    }

    /**
     * @param string $orderReferenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getPayments($orderReferenceId)
    {
        return $this->queryContainer->queryPaymentByOrderReferenceId($orderReferenceId)
            ->filterByStatus(AmazonPayConfig::STATUS_CLOSED, Criteria::NOT_EQUAL)
            ->find();
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonPayEntity
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    public function duplicatePaymentEntity(SpyPaymentAmazonpay $paymentAmazonPayEntity)
    {
        $newPaymentAmazonpay = new SpyPaymentAmazonpay();

        $paymentAmazonPayEntity->setAuthorizationReferenceId(null);
        $newPaymentAmazonpay->fromArray($paymentAmazonPayEntity->toArray());
        $paymentAmazonPayEntity->setAmazonAuthorizationId(null);
        $paymentAmazonPayEntity->save();

        $newPaymentAmazonpay->setIdPaymentAmazonpay(null);

        return $newPaymentAmazonpay;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    public function createPaymentEntity(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return $this->converter->mapTransferToEntity($amazonPayCallTransfer->getAmazonpayPayment());
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    public function loadPaymentEntity(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $paymentEntity = null;

        if ($amazonPayCallTransfer->getItems()->count()) {
            $paymentEntity = $this->queryContainer->queryPaymentBySalesOrderItemId(
                $amazonPayCallTransfer->getItems()[0]->getIdSalesOrderItem()
            )
                ->findOne();
        }

        if ($paymentEntity === null) {
            $paymentEntity = $this->queryContainer->queryPaymentByOrderReferenceId(
                $amazonPayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
            )
                ->findOne();
        }

        return $paymentEntity;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentEntity
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    public function assignAmazonpayPaymentToItems(SpyPaymentAmazonpay $paymentEntity, AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        foreach ($amazonPayCallTransfer->getItems() as $itemTransfer) {
            $paymentForItemEntity = $this->queryContainer->queryBySalesOrderItemId($itemTransfer->getIdSalesOrderItem())
                ->findOneOrCreate();
            $paymentForItemEntity->setSpyPaymentAmazonpay($paymentEntity);
            $paymentForItemEntity->save();
        }
    }
}
