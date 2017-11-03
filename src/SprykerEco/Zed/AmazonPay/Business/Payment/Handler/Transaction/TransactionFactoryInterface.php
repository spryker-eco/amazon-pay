<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

interface TransactionFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AbstractAmazonpayTransaction
     */
    public function createConfirmOrderReferenceTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AbstractAmazonpayTransaction
     */
    public function createSetOrderReferenceTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AbstractAmazonpayTransaction
     */
    public function createGetOrderReferenceDetailsTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCancelOrderTransactionSequence();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createAuthorizeTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createReauthorizeExpiredOrderTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\ReauthorizeOrderTransaction
     */
    public function createReauthorizeOrderTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCaptureAuthorizedTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createCloseCapturedOrderTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createRefundOrderTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createHandleDeclinedOrderTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionCollectionInterface
     */
    public function createConfirmPurchaseTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderRefundStatusTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderAuthorizationStatusTransaction();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    public function createUpdateOrderCaptureStatusHandler();
}
