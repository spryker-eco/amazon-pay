<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;

class AuthorizeOrderTransaction extends AbstractQuoteTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayAuthorizeOrderResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getAmazonpayPayment()->getAuthorizationDetails()->setAuthorizationReferenceId(
            $this->generateOperationReferenceId($quoteTransfer)
        );

        $quoteTransfer = parent::execute($quoteTransfer);

        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $quoteTransfer->getAmazonpayPayment()->setAuthorizationDetails(
                $this->apiResponse->getAuthorizationDetails()
            );

            if ($quoteTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()) {
                $quoteTransfer->getAmazonpayPayment()->getResponseHeader()
                    ->setIsSuccess(false)
                    ->setErrorCode($this->buildErrorCode($quoteTransfer));
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function buildErrorCode(QuoteTransfer $quoteTransfer)
    {
        return 'amazonpay.payment.error.'.
        $quoteTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getReasonCode();
    }

}
