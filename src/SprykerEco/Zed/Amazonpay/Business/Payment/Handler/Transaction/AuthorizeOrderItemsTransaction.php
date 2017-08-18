<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class AuthorizeOrderItemsTransaction extends AbstractOrderTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayAuthorizeOrderResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $orderTransfer)
    {
        $authReferenceId = $this->generateOperationReferenceId($orderTransfer);

        $this->setAuthReferenceIdToOrderItem($authReferenceId, $orderTransfer->getItems());

        $orderTransfer = parent::execute($orderTransfer);

        if ($orderTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $orderTransfer->getAmazonpayPayment()->setAuthorizationDetails(
                $this->apiResponse->getAuthorizationDetails()
            );

            if ($orderTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()) {
                $orderTransfer->getAmazonpayPayment()->getResponseHeader()
                    ->setIsSuccess(false)
                    ->setErrorCode($this->buildErrorCode($orderTransfer));
            }
        }

        return $orderTransfer;
    }

    /**
     * @param $authReferenceId
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     */
    protected function setAuthReferenceIdToOrderItem($authReferenceId, ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->getAuthorizationDetails()->setAuthorizationReferenceId($authReferenceId);
        }
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
