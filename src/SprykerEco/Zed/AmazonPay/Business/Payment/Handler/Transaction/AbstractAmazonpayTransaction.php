<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Spryker\Shared\Log\LoggerTrait;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

abstract class AbstractAmazonpayTransaction extends AbstractTransaction implements AmazonpayTransactionInterface
{
    use LoggerTrait;

    /**
     * @var \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected $paymentEntity;

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface
     */
    protected $paymentProcessor;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface $paymentProcessor
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonPayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        PaymentProcessorInterface $paymentProcessor
    ) {
        parent::__construct($executionAdapter, $config, $transactionLogger);

        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return string
     */
    protected function generateOperationReferenceId(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return uniqid($amazonPayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(), false);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $this->apiResponse = $this->executionAdapter->call($amazonPayCallTransfer);

        $amazonPayCallTransfer->getAmazonpayPayment()
            ->fromArray($this->apiResponse->modifiedToArray(), true);

        $this->transactionLogger->log(
            $amazonPayCallTransfer->getAmazonpayPayment()
        );
        $this->paymentEntity = $this->paymentProcessor->loadPaymentEntity($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonPayEntity
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return bool
     */
    protected function isPartialProcessing(SpyPaymentAmazonpay $paymentAmazonPayEntity, AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return $this->allowPartialProcessing()
            && $amazonPayCallTransfer->getItems()->count()
                !== $paymentAmazonPayEntity->getSpyPaymentAmazonpaySalesOrderItems()->count();
    }

    /**
     * @return bool
     */
    protected function allowPartialProcessing()
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return bool
     */
    protected function isPaymentSuccess(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return $amazonPayCallTransfer->getAmazonpayPayment()
            && $amazonPayCallTransfer->getAmazonpayPayment()->getResponseHeader()
            && $amazonPayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess();
    }
}
