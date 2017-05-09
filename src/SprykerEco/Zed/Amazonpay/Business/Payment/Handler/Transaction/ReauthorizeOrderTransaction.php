<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Amazonpay\AmazonpayConstants;

class ReauthorizeOrderTransaction extends AbstractOrderTransaction
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
        $orderTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->setAuthorizationReferenceId(
                $this->generateOperationReferenceId($orderTransfer)
            );

        $orderTransfer = parent::execute($orderTransfer);

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            $orderTransfer->getAmazonpayPayment()->setAuthorizationDetails(
                $this->apiResponse->getAuthorizationDetails()
            );

            $this->paymentEntity->setAmazonAuthorizationId(
                $this->apiResponse->getAuthorizationDetails()->getAmazonAuthorizationId()
            );

            $this->paymentEntity->setAuthorizationReferenceId(
                $this->apiResponse->getAuthorizationDetails()->getAuthorizationReferenceId()
            );

            $this->paymentEntity->save();
        }

        $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_PENDING);
        $this->paymentEntity->save();

        return $orderTransfer;
    }

}
