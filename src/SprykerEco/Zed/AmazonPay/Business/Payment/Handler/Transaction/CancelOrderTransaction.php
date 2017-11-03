<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class CancelOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if ($this->paymentEntity) {
            $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

            if (!$this->apiResponse->getHeader()->getIsSuccess()) {
                return $amazonpayCallTransfer;
            }

            if ($isPartialProcessing) {
                $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
            }

            $this->paymentEntity->setStatus(AmazonPayConfig::OMS_STATUS_CANCELLED);
            $this->paymentEntity->save();

            if ($isPartialProcessing) {
                $this->assignAmazonpayPaymentToItemsIfNew($this->paymentEntity, $amazonpayCallTransfer);
            }
        }

        return $amazonpayCallTransfer;
    }

    /**
     * @return bool
     */
    protected function allowPartialProcessing()
    {
        return false;
    }
}
