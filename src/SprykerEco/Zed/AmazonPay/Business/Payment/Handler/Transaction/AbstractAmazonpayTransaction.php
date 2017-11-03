<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

abstract class AbstractAmazonpayTransaction extends AbstractTransaction implements AmazonpayTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface
     */
    protected $converter;

    /**
     * @var \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected $paymentEntity;

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface $converter
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonPayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonPayQueryContainerInterface $amazonpayQueryContainer,
        AmazonPayTransferToEntityConverterInterface $converter
    ) {
        parent::__construct($executionAdapter, $config, $transactionLogger);

        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->converter = $converter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return string
     */
    protected function generateOperationReferenceId(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return uniqid($amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(), false);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $this->apiResponse = $this->executionAdapter->call($amazonpayCallTransfer);
        $amazonpayCallTransfer->getAmazonpayPayment()
            ->fromArray($this->apiResponse->modifiedToArray(), true)
            ->setResponseHeader($this->apiResponse->getHeader());

        $this->transactionsLogger->log(
            $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            $this->apiResponse->getHeader()
        );
        $this->paymentEntity = $this->loadPaymentEntity($amazonpayCallTransfer);

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return void
     */
    protected function assignAmazonpayPaymentToItemsIfNew(SpyPaymentAmazonpay $entity, AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        foreach ($amazonpayCallTransfer->getItems() as $itemTransfer) {
            $paymentForItemEntity = $this->amazonpayQueryContainer->queryBySalesOrderItemId($itemTransfer->getIdSalesOrderItem())
                ->findOneOrCreate();
            $paymentForItemEntity->setSpyPaymentAmazonpay($entity);
            $paymentForItemEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function loadPaymentEntity(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $paymentEntity = null;

        if ($amazonpayCallTransfer->getItems()->count()) {
            $paymentEntity = $this->amazonpayQueryContainer->queryPaymentBySalesOrderItemId(
                $amazonpayCallTransfer->getItems()[0]->getIdSalesOrderItem()
            )
                ->findOne();
        }

        if ($paymentEntity === null) {
            $paymentEntity = $this->amazonpayQueryContainer->queryPaymentByOrderReferenceId(
                $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
            )
                ->findOne();
        }

        return $paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentEntity(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->converter->mapTransferToEntity($amazonpayCallTransfer->getAmazonpayPayment());
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function duplicatePaymentEntity(SpyPaymentAmazonpay $paymentAmazonpay)
    {
        $newPaymentAmazonpay = new SpyPaymentAmazonpay();

        $paymentAmazonpay->setAuthorizationReferenceId(null);
        $newPaymentAmazonpay->fromArray($paymentAmazonpay->toArray());
        $paymentAmazonpay->setAmazonAuthorizationId(null);
        $paymentAmazonpay->save();

        $newPaymentAmazonpay->setIdPaymentAmazonpay(null);

        return $newPaymentAmazonpay;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return bool
     */
    protected function isPartialProcessing(SpyPaymentAmazonpay $paymentAmazonpay, AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->allowPartialProcessing()
            && $amazonpayCallTransfer->getItems()->count()
                !== $paymentAmazonpay->getSpyPaymentAmazonpaySalesOrderItems()->count();
    }

    /**
     * @return bool
     */
    protected function allowPartialProcessing()
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $payment
     *
     * @return bool
     */
    protected function isPaymentSuccess(AmazonpayCallTransfer $payment)
    {
        return $payment->getAmazonpayPayment()
            && $payment->getAmazonpayPayment()->getResponseHeader()
            && $payment->getAmazonpayPayment()->getResponseHeader()->getIsSuccess();
    }
}
