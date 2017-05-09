<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
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
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Method\AmazonpayInterface
     */
    protected $amazonpayPaymentMethod;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     */
    public function __construct(
        AdapterFactoryInterface $adapterFactory,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->config = $config;
        $this->transactionLogger = $transactionLogger;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createConfirmOrderReferenceTransaction()
    {
        return new ConfirmOrderReferenceTransaction(
            $this->adapterFactory->createConfirmOrderReferenceAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createSetOrderReferenceTransaction()
    {
        return new SetOrderReferenceDetailsTransaction(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createGetOrderReferenceDetailsTransaction()
    {
        return new GetOrderReferenceDetailsTransaction(
            $this->adapterFactory->createGetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config,
            $this->transactionLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createCancelPreOrderTransaction()
    {
        return new CancelPreOrderTransaction(
            $this->adapterFactory->createCancelPreOrderAdapter(),
            $this->config,
            $this->transactionLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCancelOrderTransaction()
    {
        return new OrderTransactionCollection(
            [
                $this->createRefundOrderTransaction(),
                $this->createAuthorizeOrderTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    protected function createCancelOrderTransactionObject()
    {
        return new CancelOrderTransaction(
            $this->adapterFactory->createCancelOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createAuthorizeOrderTransaction()
    {
        return new AuthorizeOrderTransaction(
            $this->adapterFactory->createAuthorizeQuoteAdapter(),
            $this->config,
            $this->transactionLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createReauthorizeExpiredOrderTransaction()
    {
        return new OrderTransactionCollection(
            [
                $this->createReauthorizeExpiredOrderTransaction(),
                $this->createUpdateOrderAuthorizationStatusTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    protected function createReauthorizeOrderTransaction()
    {
        return new ReauthorizeOrderTransaction(
            $this->adapterFactory->createAuthorizeCaptureNowOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createReauthorizeSuspendedOrderTransaction()
    {
        return new ReauthorizeOrderTransaction(
            $this->adapterFactory->createAuthorizeOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    protected function createCaptureOrderTransaction()
    {
        return new CaptureOrderTransaction(
            $this->adapterFactory->createCaptureOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCaptureAuthorizedTransaction()
    {
        return new OrderTransactionCollection(
            [
                $this->createUpdateOrderAuthorizationStatusTransaction(),
                $this->createCaptureOrderTransaction(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCloseOrderTransaction()
    {
        return new CloseOrderTransaction(
            $this->adapterFactory->createCloseOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createRefundOrderTransaction()
    {
        return new RefundOrderTransaction(
            $this->adapterFactory->createRefundOrderAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer,
            $this->amazonpayPaymentMethod
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createUpdateOrderRefundStatusTransaction()
    {
        return new UpdateOrderRefundStatusTransaction(
            $this->adapterFactory->createGetOrderRefundDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createUpdateOrderAuthorizationStatusTransaction()
    {
        return new UpdateOrderAuthorizationStatusTransaction(
            $this->adapterFactory->createGetOrderAuthorizationDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createUpdateOrderCaptureStatusTransaction()
    {
        return new UpdateOrderCaptureStatusTransaction(
            $this->adapterFactory->createGetOrderCaptureDetailsAdapter(),
            $this->config,
            $this->transactionLogger,
            $this->amazonpayQueryContainer
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\HandleDeclinedOrderTransaction
     */
    public function createHandleDeclinedOrderTransaction()
    {
        return new HandleDeclinedOrderTransaction(
            $this->createGetOrderReferenceDetailsTransaction(),
            $this->createCancelPreOrderTransaction()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionCollection
     */
    public function createConfirmPurchaseTransaction()
    {
        return new QuoteTransactionCollection(
            [
                $this->createSetOrderReferenceTransaction(),
                $this->createConfirmOrderReferenceTransaction(),
                $this->createGetOrderReferenceDetailsTransaction(),
                $this->createAuthorizeOrderTransaction(),
                $this->createHandleDeclinedOrderTransaction(),
            ]
        );
    }

}
