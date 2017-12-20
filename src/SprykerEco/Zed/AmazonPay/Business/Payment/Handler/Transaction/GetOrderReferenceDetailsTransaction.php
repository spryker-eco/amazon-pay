<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;

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

        if (!$this->isPaymentSuccess($amazonPayCallTransfer)) {
            return $amazonPayCallTransfer;
        }

        $this->saveAddresses($amazonPayCallTransfer);

        $amazonPayCallTransfer->getAmazonpayPayment()->setIsSandbox(
            $this->apiResponse->getIsSandbox()
        );
        $amazonPayCallTransfer->setOrderReference(
            $amazonPayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
        );

        $this->saveOrderReferenceStatus($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function saveOrderReferenceStatus(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $orderReferenceStatus = new AmazonpayStatusTransfer();
        $orderReferenceStatus->setState(
            $this->apiResponse->getOrderReferenceStatus()->getState()
        );

        $amazonPayCallTransfer->getAmazonpayPayment()->setOrderReferenceStatus($orderReferenceStatus);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function saveAddresses(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer->setShippingAddress($this->apiResponse->getShippingAddress());
        $amazonPayCallTransfer->setBillingAddress(
            $this->apiResponse->getBillingAddress() ?? $this->apiResponse->getShippingAddress()
        );
        $amazonPayCallTransfer->setBillingSameAsShipping($this->apiResponse->getBillingAddress() !== null);
    }
}
