<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger;

use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayApiLog;

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
     * @param string $reportLevel
     */
    public function __construct($reportLevel)
    {
        $this->reportLevel = $reportLevel;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $headerTransfer
     *
     * @return bool
     */
    protected function isLoggingEnabled(AmazonpayResponseHeaderTransfer $headerTransfer)
    {
        if ($this->reportLevel === static::REPORT_LEVEL_ALL) {
            return true;
        };

        if ($this->reportLevel === static::REPORT_LEVEL_DISABLED) {
            return false;
        };

        if ($this->reportLevel === static::REPORT_LEVEL_ERRORS_ONLY) {
            return !$headerTransfer->getIsSuccess();
        }

        return false;
    }

    /**
     * @param string $orderReferenceId
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $headerTransfer
     *
     * @return void
     */
    public function log($orderReferenceId, AmazonpayResponseHeaderTransfer $headerTransfer)
    {
        if (!$this->isLoggingEnabled($headerTransfer)) {
            return;
        }

        $logEntity = new SpyPaymentAmazonpayApiLog();
        $logEntity->setOrderReferenceId($orderReferenceId);
        $logEntity->setStatusCode($headerTransfer->getStatusCode());
        $logEntity->setRequestId($headerTransfer->getRequestId());
        $logEntity->setErrorMessage($headerTransfer->getErrorMessage());
        $logEntity->setErrorCode($headerTransfer->getErrorCode());
        $logEntity->save();
    }
}
