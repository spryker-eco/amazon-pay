<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class AmazonpayDataQuoteInitializer implements QuoteUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getAmazonpayPayment()->getAuthorizationDetails()) {
            $quoteTransfer->getAmazonpayPayment()->setAuthorizationDetails(
                $this->createAmazonpayAuthorizationDetailsTransfer()
            );
        }

        if (!$quoteTransfer->getAmazonpayPayment()->getCaptureDetails()) {
            $quoteTransfer->getAmazonpayPayment()->setCaptureDetails(
                $this->createAmazonpayCaptureDetailsTransfer()
            );
        }

        if (!$quoteTransfer->getAmazonpayPayment()->getRefundDetails()) {
            $quoteTransfer->getAmazonpayPayment()->setRefundDetails(
                $this->createAmazonpayRefundDetailsTransfer()
            );
        }

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer
     */
    protected function createAmazonpayAuthorizationDetailsTransfer()
    {
        $amazonpayAuthorizationDetails = new AmazonpayAuthorizationDetailsTransfer();
        $amazonpayAuthorizationDetails->setAuthorizationStatus($this->createStatusTransfer());

        return $amazonpayAuthorizationDetails;
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer
     */
    protected function createAmazonpayCaptureDetailsTransfer()
    {
        $amazonpayCaptureDetails = new AmazonpayCaptureDetailsTransfer();
        $amazonpayCaptureDetails->setCaptureStatus($this->createStatusTransfer());

        return $amazonpayCaptureDetails;
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer
     */
    protected function createAmazonpayRefundDetailsTransfer()
    {
        $amazonpayRefundDetails = new AmazonpayRefundDetailsTransfer();
        $amazonpayRefundDetails->setRefundStatus($this->createStatusTransfer());

        return $amazonpayRefundDetails;
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function createStatusTransfer()
    {
        return new AmazonpayStatusTransfer();
    }
}
