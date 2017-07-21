<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class GetOrderReferenceDetailsTransaction extends AbstractAmazonpayTransaction 
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if ($amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $amazonpayCallTransfer->setShippingAddress($this->apiResponse->getShippingAddress());

            if ($this->apiResponse->getBillingAddress()) {
                $amazonpayCallTransfer->setBillingAddress($this->apiResponse->getBillingAddress());
            } else {
                $amazonpayCallTransfer->setBillingAddress($this->apiResponse->getShippingAddress());
                $amazonpayCallTransfer->setBillingSameAsShipping(true);
            }

            $amazonpayCallTransfer->getAmazonpayPayment()->setIsSandbox(
                $this->apiResponse->getIsSandbox()
            );
            $amazonpayCallTransfer->setOrderReference(
                $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId()
            );

            $orderReferenceStatus = new AmazonpayStatusTransfer();
            $orderReferenceStatus->setState($this->apiResponse->getOrderReferenceStatus());
            $orderReferenceStatus->setIsOpen(
                $this->apiResponse->getOrderReferenceStatus() === AmazonpayConstants::ORDER_REFERENCE_STATUS_OPEN
            );

            $amazonpayCallTransfer->getAmazonpayPayment()->setOrderReferenceStatus($orderReferenceStatus);
        }

        return $amazonpayCallTransfer;
    }

}
