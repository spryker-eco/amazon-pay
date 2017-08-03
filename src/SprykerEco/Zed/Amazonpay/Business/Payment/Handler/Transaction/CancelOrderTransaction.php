<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class CancelOrderTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_CANCELLED);
            $this->paymentEntity->save();
        }

        return $amazonpayCallTransfer;
    }

}
