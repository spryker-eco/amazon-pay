<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class UpdateOrderAuthorizationStatusTransaction extends AbstractOrderTransaction
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
        $orderTransfer = parent::execute($orderTransfer);

        $orderTransfer->getAmazonpayPayment()->setAuthorizationDetails(
            $this->apiResponse->getAuthorizationDetails()
        );

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            if ($this->apiResponse->getAuthorizationDetails()->getIdList()) {
                $this->paymentEntity->setAmazonCaptureId(
                    $this->apiResponse->getAuthorizationDetails()->getIdList()
                );

                $this->paymentEntity->setStatus(
                    AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED
                );

                $this->paymentEntity->save();

                return $orderTransfer;
            }

            $status = $this->apiResponse->getAuthorizationDetails()->getAuthorizationStatus();

            if ($status->getIsPending()) {
                return $orderTransfer;
            }

            if ($status->getIsDeclined()) {
                if ($status->getIsSuspended()) {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED);
                } else {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_DECLINED);
                }
            }

            if ($status->getIsOpen()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_OPEN);
            }

            if ($status->getIsClosed()) {
                if ($status->getIsReauthorizable()) {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_EXPIRED);
                } else {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_CLOSED);
                }
            }

            $this->paymentEntity->save();
        }

        return $orderTransfer;
    }

}
