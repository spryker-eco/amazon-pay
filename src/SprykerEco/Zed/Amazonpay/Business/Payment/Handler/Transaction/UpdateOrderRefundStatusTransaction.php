<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class UpdateOrderRefundStatusTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!$amazonpayCallTransfer->getAmazonpayPayment()->getRefundDetails()->getRefundStatus()->getIsPending()) {
            return $amazonpayCallTransfer;
        }

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            if ($this->apiResponse->getRefundDetails()->getRefundStatus()->getIsPending()) {
                return $amazonpayCallTransfer;
            }

            if ($this->apiResponse->getRefundDetails()->getRefundStatus()->getIsDeclined()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_REFUND_DECLINED);
            }

            if ($this->apiResponse->getRefundDetails()->getRefundStatus()->getIsCompleted()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_REFUND_COMPLETED);
            }

            $this->paymentEntity->save();
        }

        return $amazonpayCallTransfer;
    }

}
