<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class CloseOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }
        $this->paymentEntity->setStatus(AmazonpayConfig::OMS_STATUS_CLOSED);
        $this->paymentEntity->save();

        $this->closeAllPaymentsForThisOrder($this->paymentEntity->getOrderReferenceId());

        return $amazonpayCallTransfer;
    }

    /**
     * @param string $orderReferenceId
     *
     * @return void
     */
    protected function closeAllPaymentsForThisOrder($orderReferenceId)
    {
        $payments = $this->amazonpayQueryContainer->queryPaymentByOrderReferenceId($orderReferenceId)
            ->filterByStatus(AmazonpayConfig::OMS_STATUS_CLOSED, Criteria::NOT_EQUAL)
            ->find();

        foreach ($payments as $payment) {
            $payment->setStatus(AmazonpayConfig::OMS_STATUS_CLOSED);
            $payment->save();
        }
    }

    /**
     * @return bool
     */
    protected function allowPartialProcessing()
    {
        return false;
    }
}
