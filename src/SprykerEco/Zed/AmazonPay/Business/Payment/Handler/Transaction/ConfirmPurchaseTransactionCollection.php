<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Writer\AmazonpayPaymentWriterInterface;

class ConfirmPurchaseTransactionCollection extends AbstractTransactionCollection implements TransactionCollectionInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface
     */
    protected $converter;

    /**
     * @var AmazonpayPaymentWriterInterface
     */
    protected $amazonpayPaymentSaver;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[] $transactionHandlers
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface $converter
     * @param AmazonpayPaymentWriterInterface $amazonpayPaymentSaver
     */
    public function __construct(
        array $transactionHandlers,
        AmazonPayConverterInterface $converter,
        AmazonpayPaymentWriterInterface $amazonpayPaymentSaver
    ) {
        parent::__construct($transactionHandlers);
        $this->converter = $converter;
        $this->amazonpayPaymentSaver = $amazonpayPaymentSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $amazonpayCallTransfer = $this->converter->mapToAmazonpayCallTransfer($quoteTransfer);
        $amazonpayCallTransfer = $this->executeHandlers($amazonpayCallTransfer);
        $quoteTransfer->fromArray($amazonpayCallTransfer->modifiedToArray(), true);

        $this->amazonpayPaymentSaver->writerConfirmedAmazonPayPayment($quoteTransfer->getAmazonpayPayment());

        return $quoteTransfer;
    }
}
