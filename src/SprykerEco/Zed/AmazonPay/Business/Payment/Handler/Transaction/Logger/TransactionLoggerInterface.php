<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;

interface TransactionLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function log(AmazonpayPaymentTransfer $amazonpayPaymentTransfer);
}
