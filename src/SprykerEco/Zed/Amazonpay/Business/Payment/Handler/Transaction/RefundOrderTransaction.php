<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Amazonpay\AmazonpayConstants;

class RefundOrderTransaction extends AbstractOrderTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayRefundOrderResponseTransfer
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
                ->getCaptureDetails()
                ->getAmazonCaptureId()
        ) {
            return $orderTransfer;
        }

        $orderTransfer->getAmazonpayPayment()->getRefundDetails()->setRefundReferenceId(
            $this->generateOperationReferenceId($orderTransfer)
        );

        $orderTransfer = parent::execute($orderTransfer);

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_REFUND_PENDING);
            $this->paymentEntity->setAmazonRefundId($this->apiResponse->getRefundDetails()->getAmazonRefundId());
            $this->paymentEntity->setRefundReferenceId($this->apiResponse->getRefundDetails()->getRefundReferenceId());
            $this->paymentEntity->save();
        }

        return $orderTransfer;
    }

}
