<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class GetOrderReferenceDetailsTransaction extends AbstractQuoteTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer = parent::execute($quoteTransfer);

        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $quoteTransfer->setShippingAddress($this->apiResponse->getShippingAddress());

            if ($this->apiResponse->getBillingAddress()) {
                $quoteTransfer->setBillingAddress($this->apiResponse->getBillingAddress());
            } else {
                $quoteTransfer->setBillingAddress($this->apiResponse->getShippingAddress());
                $quoteTransfer->setBillingSameAsShipping(true);
            }

            $quoteTransfer->getAmazonpayPayment()->setIsSandbox(
                $this->apiResponse->getIsSandbox()
            );
            $quoteTransfer->setOrderReference(
                $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId()
            );

            $orderReferenceStatus = new AmazonpayStatusTransfer();
            $orderReferenceStatus->setState($this->apiResponse->getOrderReferenceStatus());
            $orderReferenceStatus->setIsOpen(
                $this->apiResponse->getOrderReferenceStatus() === AmazonpayConstants::ORDER_REFERENCE_STATUS_OPEN
            );

            $quoteTransfer->getAmazonpayPayment()->setOrderReferenceStatus($orderReferenceStatus);
        }

        return $quoteTransfer;
    }

}
