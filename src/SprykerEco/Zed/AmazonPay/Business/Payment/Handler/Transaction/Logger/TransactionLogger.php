<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayApiLog;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;

class TransactionLogger implements TransactionLoggerInterface
{
    const REPORT_LEVEL_ALL = 'ALL';
    const REPORT_LEVEL_ERRORS_ONLY = 'ERRORS_ONLY';
    const REPORT_LEVEL_DISABLED = 'DISABLED';

    /**
     * @var string
     */
    protected $reportLevel;

    /**
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     */
    public function __construct(AmazonPayConfigInterface $config)
    {
        $this->reportLevel = $config->getErrorReportLevel();
    }

    /**
     * @return array
     */
    protected function loggingEnabledMap()
    {
        return [
            static::REPORT_LEVEL_ALL => function () {
                return true;
            },
            static::REPORT_LEVEL_DISABLED => function () {
                return false;
            },
            static::REPORT_LEVEL_ERRORS_ONLY => function (AmazonpayResponseHeaderTransfer $headerTransfer) {
                return !$headerTransfer->getIsSuccess();
            },
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $headerTransfer
     *
     * @return bool
     */
    protected function isLoggingEnabled(AmazonpayResponseHeaderTransfer $headerTransfer)
    {
        return $this->loggingEnabledMap()[$this->reportLevel]($headerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function log(AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        if (!$this->isLoggingEnabled($amazonpayPaymentTransfer->getResponseHeader())) {
            return;
        }

        $this->storeLogEntry($amazonpayPaymentTransfer->getOrderReferenceId(), $amazonpayPaymentTransfer->getResponseHeader());
    }

    /**
     * @param string $orderReferenceId
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $headerTransfer
     *
     * @return void
     */
    protected function storeLogEntry($orderReferenceId, AmazonpayResponseHeaderTransfer $headerTransfer)
    {
        $logEntity = new SpyPaymentAmazonpayApiLog();
        $logEntity->setOrderReferenceId($orderReferenceId);
        $logEntity->setStatusCode($headerTransfer->getStatusCode());
        $logEntity->setRequestId($headerTransfer->getRequestId());
        $logEntity->setErrorMessage($headerTransfer->getErrorMessage());
        $logEntity->setErrorCode($headerTransfer->getErrorCode());
        $logEntity->save();
    }
}
