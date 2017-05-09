<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Calculation\CalculationClientInterface;

class AmazonpayToCalculationBridge implements AmazonpayToCalculationInterface
{

    /**
     * @var \Spryker\Client\Calculation\CalculationClientInterface
     */
    protected $calculationClient;

    public function __construct(CalculationClientInterface $calculationClient)
    {
        $this->calculationClient = $calculationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->calculationClient->recalculate($quoteTransfer);
    }
}
