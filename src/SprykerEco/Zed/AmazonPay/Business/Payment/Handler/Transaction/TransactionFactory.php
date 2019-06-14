<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

class TransactionFactory implements TransactionFactoryInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface
     */
    protected $adapterFactory;

    /**
     * @var \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface
     */
    protected $converter;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface
     */
    protected $toEntityConverter;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface
     */
    protected $paymentProcessor;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface $converter
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface $toEntityConverter
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface $refundOrderModel
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface $paymentProcessor
     */
    public function __construct(
        AdapterFactoryInterface $adapterFactory,
        AmazonPayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonPayConverterInterface $converter,
        AmazonPayTransferToEntityConverterInterface $toEntityConverter,
        RefundOrderInterface $refundOrderModel,
        PaymentProcessorInterface $paymentProcessor
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->config = $config;
        $this->transactionLogger = $transactionLogger;
        $this->converter = $converter;
        $this->toEntityConverter = $toEntityConverter;
        $this->refundOrderModel = $refundOrderModel;
        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createConfirmOrderReferenceTransaction()
    {
        return new ConfirmOrderReferenceTransaction(
            $this->adapterFactory->createConfirmOrderReferenceAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createSetOrderReferenceTransaction()
    {
        return new SetOrderReferenceDetailsTransaction(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createGetOrderReferenceDetailsTransaction()
    {
        return new GetOrderReferenceDetailsTransaction(
            $this->adapterFactory->createGetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
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
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createCancelOrderTransaction()
    {
        return new CancelOrderTransaction(
            $this->adapterFactory->createCancelOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createAuthorizeTransaction()
    {
        return new AuthorizeTransaction(
            $this->adapterFactory->createAuthorizeAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
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
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createReauthorizeOrderTransaction()
    {
        return new ReauthorizeOrderTransaction(
            $this->adapterFactory->createAuthorizeAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createCaptureOrderTransaction()
    {
        return new CaptureOrderTransaction(
            $this->adapterFactory->createCaptureOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createAuthorizeCaptureNowTransaction()
    {
        return new AuthorizeOrderIfRequiredTransaction(
            $this->adapterFactory->createAuthorizeCaptureNowAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
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
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
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
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected function createCloseOrderTransaction()
    {
        return new CloseOrderTransaction(
            $this->adapterFactory->createCloseOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createRefundOrderTransaction()
    {
        return new RefundOrderTransaction(
            $this->adapterFactory->createRefundOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderRefundStatusTransaction()
    {
        return new UpdateOrderRefundStatusTransaction(
            $this->adapterFactory->createGetOrderRefundDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor,
            $this->refundOrderModel
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderAuthorizationStatusTransaction()
    {
        return new UpdateOrderAuthorizationStatusTransaction(
            $this->adapterFactory->createGetOrderAuthorizationDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
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
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderCaptureStatusTransaction()
    {
        return new UpdateOrderCaptureStatusTransaction(
            $this->adapterFactory->createGetOrderCaptureDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->paymentProcessor
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createHandleDeclinedOrderTransaction()
    {
        return new HandleDeclinedOrderTransaction(
            $this->createGetOrderReferenceDetailsTransaction(),
            $this->createCancelOrderTransaction()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionCollectionInterface
     */
    public function createConfirmPurchaseTransaction()
    {
        return new TransactionCollection(
            [
                $this->createSetOrderReferenceTransaction(),
                $this->createConfirmOrderReferenceTransaction(),
//                $this->createGetOrderReferenceDetailsTransaction(),
//                $this->createAuthorizeTransaction(),
//                $this->createHandleDeclinedOrderTransaction(),
            ],
            $this->converter
        );
    }
}
