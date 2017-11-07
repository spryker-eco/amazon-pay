<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class CloseOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonPayCallTransfer;
        }

        $this->paymentProcessor->updateStatus(
            $amazonPayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            AmazonPayConfig::OMS_STATUS_CLOSED
        );

        return $amazonPayCallTransfer;
    }

    /**
     * @return bool
     */
    protected function allowPartialProcessing()
    {
        return false;
    }
}
