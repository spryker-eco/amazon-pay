<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Amazonpay\AmazonpayConstants;

class CaptureOrderTransaction extends AbstractOrderTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayCaptureOrderResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $orderTransfer)
    {
        if (!$orderTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsOpen()
        ) {
            return $orderTransfer;
        }

        $orderTransfer->getAmazonpayPayment()->getCaptureDetails()->setCaptureReferenceId(
            $this->generateOperationReferenceId($orderTransfer)
        );

        $orderTransfer = parent::execute($orderTransfer);

        if ($orderTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $orderTransfer->getAmazonpayPayment()->setCaptureDetails(
                $this->apiResponse->getCaptureDetails()
            );

            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );

            $this->paymentEntity->setCaptureReferenceId(
                $this->apiResponse->getCaptureDetails()->getCaptureReferenceId()
            );
        }

        if ($orderTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()->getIsDeclined()) {
            $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED);
        } elseif ($orderTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()->getIsPending()) {
            $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING);
        } elseif ($orderTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()->getIsCompleted()) {
            $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED);
        }

        $this->paymentEntity->save();

        return $orderTransfer;
    }

}
