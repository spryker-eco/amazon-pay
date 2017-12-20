<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

abstract class AbstractTransactionCollection
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[]
     */
    protected $transactionHandlers;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[] $transactionHandlers
     */
    public function __construct(
        array $transactionHandlers
    ) {
        $this->transactionHandlers = $transactionHandlers;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function executeHandlers(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer->getAmazonpayPayment()->setResponseHeader(null);

        foreach ($this->transactionHandlers as $transactionHandler) {
            $amazonPayCallTransfer = $transactionHandler->execute($amazonPayCallTransfer);

            if ($amazonPayCallTransfer->getAmazonpayPayment()->getResponseHeader() &&
                !$amazonPayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                break;
            }
        }

        return $amazonPayCallTransfer;
    }
}
