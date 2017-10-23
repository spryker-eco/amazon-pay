<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger;

use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;

interface TransactionLoggerInterface
{
    /**
     * @param string $orderReferenceId
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $headerTransfer
     *
     * @return void
     */
    public function log($orderReferenceId, AmazonpayResponseHeaderTransfer $headerTransfer);
}
