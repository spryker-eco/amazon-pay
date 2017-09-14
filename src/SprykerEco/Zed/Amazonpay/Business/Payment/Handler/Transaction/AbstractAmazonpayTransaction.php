<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Exception;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

abstract class AbstractAmazonpayTransaction extends AbstractTransaction implements AmazonpayTransactionInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface
     */
    protected $converter;

    /**
     * @var \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected $paymentEntity;

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface $converter
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        AmazonpayTransferToEntityConverterInterface $converter
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
        // TODO: remove this hidden call
        $this->loadPaymentEntity($amazonpayCallTransfer);

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $entity
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
     * @throws \Exception
     *
     * @return void
     */
    protected function loadPaymentEntity(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if ($this->paymentEntity) {
            throw new Exception('paymentEntity was previously defined!');
        }

        $this->paymentEntity = null;

        if ($amazonpayCallTransfer->getItems()->count()) {
            $this->paymentEntity = $this->amazonpayQueryContainer->queryPaymentBySalesOrderItemId(
                $amazonpayCallTransfer->getItems()[0]->getIdSalesOrderItem()
            )
                ->findOne();
        }

        if ($this->paymentEntity === null) {
            $this->paymentEntity = $this->amazonpayQueryContainer->queryPaymentByOrderReferenceId(
                $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
            )
                ->findOne();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentEntity(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $paymentEntity = $this->converter->mapTransferToEntity($amazonpayCallTransfer->getAmazonpayPayment());

        return $paymentEntity;
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
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
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return bool
     */
    protected function isPartialProcessing(SpyPaymentAmazonpay $paymentAmazonpay, AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $amazonpayCallTransfer->getItems()->count() !== $paymentAmazonpay->getSpyPaymentAmazonpaySalesOrderItems()->count();
    }

}
