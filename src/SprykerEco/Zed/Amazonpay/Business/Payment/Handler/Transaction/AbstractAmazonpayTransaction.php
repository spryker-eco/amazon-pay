<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

abstract class AbstractAmazonpayTransaction extends AbstractTransaction implements AmazonpayTransactionInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

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
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer
    ) {
        parent::__construct($executionAdapter, $config, $transactionLogger);

        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
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
        $amazonpayCallTransfer->getAmazonpayPayment()->setResponseHeader($this->apiResponse->getHeader());
        $this->transactionsLogger->log(
            $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            $this->apiResponse->getHeader()
        );
        // TODO: remove this hidden call
        $this->loadPaymentEntity($amazonpayCallTransfer);

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function loadPaymentEntity(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if ($this->paymentEntity === null) {
            $this->paymentEntity = $this->amazonpayQueryContainer->queryPaymentByOrderReferenceId(
                $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
            )
                ->findOne();
        }

        return $this->paymentEntity;
    }

}
