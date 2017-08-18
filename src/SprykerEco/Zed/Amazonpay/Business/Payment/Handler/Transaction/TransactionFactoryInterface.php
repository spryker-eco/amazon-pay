<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

interface TransactionFactoryInterface
{

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractAmazonpayTransaction
     */
    public function createConfirmOrderReferenceTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractAmazonpayTransaction
     */
    public function createSetOrderReferenceTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractAmazonpayTransaction
     */
    public function createGetOrderReferenceDetailsTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCancelPreOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCancelOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createAuthorizeTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createReauthorizeExpiredOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\ReauthorizeOrderTransaction
     */
    public function createReauthorizeOrderTransactionObject();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCaptureAuthorizedTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCloseCapturedOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createRefundOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\HandleDeclinedOrderTransaction
     */
    public function createHandleDeclinedOrderTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\TransactionCollection
     */
    public function createConfirmPurchaseTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderRefundStatusTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderAuthorizationStatusTransaction();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderCaptureStatusTransaction();

}
