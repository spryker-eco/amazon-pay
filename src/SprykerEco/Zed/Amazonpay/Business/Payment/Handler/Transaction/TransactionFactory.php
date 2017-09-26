<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayConverterInterface;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

class TransactionFactory implements TransactionFactoryInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface
     */
    protected $toEntityConverter;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayConverterInterface $converter
     * @param \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface $toEntityConverter
     */
    public function __construct(
        AdapterFactoryInterface $adapterFactory,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        AmazonpayConverterInterface $converter,
        AmazonpayTransferToEntityConverterInterface $toEntityConverter
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->config = $config;
        $this->transactionLogger = $transactionLogger;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->converter = $converter;
        $this->toEntityConverter = $toEntityConverter;
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createConfirmOrderReferenceTransaction()
    {
        return new ConfirmOrderReferenceTransaction(
            $this->adapterFactory->createConfirmOrderReferenceAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createSetOrderReferenceTransaction()
    {
        return new SetOrderReferenceDetailsTransaction(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createGetOrderReferenceDetailsTransaction()
    {
        return new GetOrderReferenceDetailsTransaction(
            $this->adapterFactory->createGetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCancelPreOrderTransaction()
    {
        return new CancelPreOrderTransaction(
            $this->adapterFactory->createCancelPreOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCancelOrderTransactionSequence()
    {
        return new TransactionSequence(
            [
                $this->createRefundOrderTransaction(),
                $this->createCancelOrderTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createCancelOrderTransaction()
    {
        return new CancelOrderTransaction(
            $this->adapterFactory->createCancelOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createAuthorizeTransaction()
    {
        return new AuthorizeTransaction(
            $this->adapterFactory->createAuthorizeAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createReauthorizeExpiredOrderTransaction()
    {
        return new TransactionSequence(
            [
                $this->createReauthorizeOrderTransaction(),
                $this->createUpdateOrderAuthorizationStatusTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createReauthorizeOrderTransaction()
    {
        return new ReauthorizeOrderTransaction(
            $this->adapterFactory->createAuthorizeAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createCaptureOrderTransaction()
    {
        return new CaptureOrderTransaction(
            $this->adapterFactory->createCaptureOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createAuthorizeCaptureNowTransaction()
    {
        return new AuthorizeOrderIfRequiredTransaction(
            $this->adapterFactory->createAuthorizeCaptureNowAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCaptureAuthorizedTransaction()
    {
        return new TransactionSequence(
            [
                $this->createAuthorizeCaptureNowTransaction(),
                $this->createUpdateOrderCaptureStatusTransaction(),
                $this->createCaptureOrderTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCloseCapturedOrderTransaction()
    {
        return new TransactionSequence(
            [
                $this->createGetOrderReferenceDetailsTransaction(),
                $this->createCloseOrderTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createCloseOrderTransaction()
    {
        return new CloseOrderTransaction(
            $this->adapterFactory->createCloseOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createRefundOrderTransaction()
    {
        return new RefundOrderTransaction(
            $this->adapterFactory->createRefundOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderRefundStatusTransaction()
    {
        return new UpdateOrderRefundStatusTransaction(
            $this->adapterFactory->createGetOrderRefundDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderAuthorizationStatusTransaction()
    {
        return new UpdateOrderAuthorizationStatusTransaction(
            $this->adapterFactory->createGetOrderAuthorizationDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderCaptureStatusHandler()
    {
        return new TransactionSequence(
            [
                $this->createUpdateOrderAuthorizationStatusTransaction(),
                $this->createUpdateOrderCaptureStatusTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderCaptureStatusTransaction()
    {
        return new UpdateOrderCaptureStatusTransaction(
            $this->adapterFactory->createGetOrderCaptureDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->toEntityConverter
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createHandleDeclinedOrderTransaction()
    {
        return new HandleDeclinedOrderTransaction(
            $this->createGetOrderReferenceDetailsTransaction(),
            $this->createCancelPreOrderTransaction()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\TransactionCollectionInterface
     */
    public function createConfirmPurchaseTransaction()
    {
        return new TransactionCollection(
            [
                $this->createSetOrderReferenceTransaction(),
                $this->createConfirmOrderReferenceTransaction(),
                $this->createGetOrderReferenceDetailsTransaction(),
                $this->createAuthorizeTransaction(),
                $this->createHandleDeclinedOrderTransaction(),
            ],
            $this->converter
        );
    }

}
