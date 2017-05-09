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
     * @var \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractQuoteTransaction[]
     */
    protected $transactionHandlers;

    /**
     * @param \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractQuoteTransaction[] $transactionHandlers
     */
    public function __construct(
        array $transactionHandlers
    ) {
        $this->transactionHandlers = $transactionHandlers;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function executeHandlers(AbstractTransfer $abstractTransfer)
    {
        foreach ($this->transactionHandlers as $transactionHandler) {
            $abstractTransfer = $transactionHandler->execute($abstractTransfer);

            if (!$abstractTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                return $abstractTransfer;
            }
        }

        return $abstractTransfer;
    }

}
