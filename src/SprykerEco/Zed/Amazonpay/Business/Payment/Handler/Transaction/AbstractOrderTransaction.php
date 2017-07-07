<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

abstract class AbstractOrderTransaction extends AbstractTransaction implements OrderTransactionInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected $paymentEntity;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected $apiResponse;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     */
    public function __construct(
        OrderAdapterInterface $executionAdapter,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer
    ) {
        parent::__construct($executionAdapter, $config, $transactionLogger);

        $this->queryContainer = $amazonpayQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function generateOperationReferenceId(OrderTransfer $orderTransfer)
    {
        return uniqid($orderTransfer->getAmazonpayPayment()->getOrderReferenceId(), false);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $orderTransfer)
    {
        $this->apiResponse = $this->executionAdapter->call($orderTransfer);
        $orderTransfer->getAmazonpayPayment()->setResponseHeader($this->apiResponse->getHeader());
        $this->transactionsLogger->log(
            $orderTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            $this->apiResponse->getHeader()
        );
        // TODO: remove this hidden call
        $this->retrievePaymentEntity($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(OrderTransfer $orderTransfer)
    {
        if ($this->paymentEntity === null) {
            $this->paymentEntity = $this->queryContainer->queryPaymentByOrderReferenceId(
                $orderTransfer->getAmazonpayPayment()->getOrderReferenceId()
            )
                ->findOne();
        }

        return $this->paymentEntity;
    }

}
