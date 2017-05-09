<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

interface TransactionFactoryInterface
{

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createConfirmOrderReferenceTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createSetOrderReferenceTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    public function createGetOrderReferenceDetailsTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCancelPreOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCancelOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AuthorizeOrderTransaction
     */
    public function createAuthorizeOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createReauthorizeExpiredOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\ReauthorizeOrderTransaction
     */
    public function createReauthorizeSuspendedOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCaptureAuthorizedTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createCloseOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createRefundOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\HandleDeclinedOrderTransaction
     */
    public function createHandleDeclinedOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionCollection
     */
    public function createConfirmPurchaseTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createUpdateOrderRefundStatusTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createUpdateOrderAuthorizationStatusTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\OrderTransactionInterface
     */
    public function createUpdateOrderCaptureStatusTransaction();

}
