<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractTransactionCollection
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractQuoteTransaction[]
     */
    protected $transactionHandlers;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractQuoteTransaction[] $transactionHandlers
     */
    public function __construct(
        array $transactionHandlers
    ) {
        $this->transactionHandlers = $transactionHandlers;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeHandlers(AbstractTransfer $abstractTransfer)
    {
        $abstractTransfer->getAmazonpayPayment()->setResponseHeader(null);

        foreach ($this->transactionHandlers as $transactionHandler) {
            $abstractTransfer = $transactionHandler->execute($abstractTransfer);

            if ($abstractTransfer->getAmazonpayPayment()->getResponseHeader() &&
                !$abstractTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                break;
            }
        }

        return $abstractTransfer;
    }

}
