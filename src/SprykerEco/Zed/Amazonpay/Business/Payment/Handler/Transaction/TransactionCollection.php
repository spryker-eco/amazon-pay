<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayConverterInterface;

class TransactionCollection extends AbstractTransactionCollection
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayConverterInterface
     */
    protected $converter;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[] $transactionHandlers
     * @param \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayConverterInterface $converter
     */
    public function __construct(
        array $transactionHandlers,
        AmazonpayConverterInterface $converter
    ) {
        parent::__construct($transactionHandlers);

        $this->converter = $converter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer)
    {
        $amazonpayCallTransfer = $this->converter->mapToAmazonpayCallTransfer($quoteTransfer);
        $amazonpayCallTransfer = $this->executeHandlers($amazonpayCallTransfer);
        $quoteTransfer->fromArray($amazonpayCallTransfer->modifiedToArray(), true);

        return $quoteTransfer;
    }

}
