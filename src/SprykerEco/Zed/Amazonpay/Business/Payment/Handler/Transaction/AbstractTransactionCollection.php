<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

abstract class AbstractTransactionCollection
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[]
     */
    protected $transactionHandlers;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[] $transactionHandlers
     */
    public function __construct(
        array $transactionHandlers
    ) {
        $this->transactionHandlers = $transactionHandlers;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function executeHandlers(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer->getAmazonpayPayment()->setResponseHeader(null);

        foreach ($this->transactionHandlers as $transactionHandler) {
            $amazonpayCallTransfer = $transactionHandler->execute($amazonpayCallTransfer);

            if ($amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader() &&
                !$amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                break;
            }
        }

        return $amazonpayCallTransfer;
    }

}
