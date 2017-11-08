<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class GetOrderReferenceDetailsTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        if ($this->isPaymentSuccess($amazonPayCallTransfer)) {
            $amazonPayCallTransfer->setShippingAddress($this->apiResponse->getShippingAddress());

            if ($this->apiResponse->getBillingAddress()) {
                $amazonPayCallTransfer->setBillingAddress($this->apiResponse->getBillingAddress());
            } else {
                $amazonPayCallTransfer->setBillingAddress($this->apiResponse->getShippingAddress());
                $amazonPayCallTransfer->setBillingSameAsShipping(true);
            }

            $amazonPayCallTransfer->getAmazonpayPayment()->setIsSandbox(
                $this->apiResponse->getIsSandbox()
            );
            $amazonPayCallTransfer->setOrderReference(
                $amazonPayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
            );

            $state = $this->apiResponse->getOrderReferenceStatus()->getState();

            $orderReferenceStatus = new AmazonpayStatusTransfer();
            $orderReferenceStatus->setState($state);

            $amazonPayCallTransfer->getAmazonpayPayment()->setOrderReferenceStatus($orderReferenceStatus);
        }

        return $amazonPayCallTransfer;
    }
}
