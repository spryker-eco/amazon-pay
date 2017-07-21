<?php


/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class UpdateOrderCaptureStatusTransaction extends AbstractAmazonpayTransaction
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
        $orderTransfer = parent::execute($orderTransfer);

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            if ($this->apiResponse->getCaptureDetails()->getCaptureStatus()->getIsPending()) {
                return $orderTransfer;
            }

            if ($this->apiResponse->getCaptureDetails()->getIdList()) {
                $this->paymentEntity->setAmazonCaptureId(
                    $this->apiResponse->getCaptureDetails()->getIdList()
                );
            }

            if ($this->apiResponse->getCaptureDetails()->getCaptureStatus()->getIsDeclined()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED);
            }

            if ($this->apiResponse->getCaptureDetails()->getCaptureStatus()->getIsCompleted()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED);
            }

            if ($this->apiResponse->getCaptureDetails()->getCaptureStatus()->getIsClosed()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CAPTURE_CLOSED);
            }

            $this->paymentEntity->save();
        }

        return $orderTransfer;
    }

}
