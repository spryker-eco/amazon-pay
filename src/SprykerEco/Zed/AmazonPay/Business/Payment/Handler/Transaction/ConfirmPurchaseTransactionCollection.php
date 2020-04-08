<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface;

class ConfirmPurchaseTransactionCollection extends AbstractTransactionCollection implements TransactionCollectionInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface
     */
    protected $converter;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface[] $transactionHandlers
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface $converter
     */
    public function __construct(
        array $transactionHandlers,
        AmazonPayConverterInterface $converter
    ) {
        parent::__construct($transactionHandlers);
        $this->converter = $converter;
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

        foreach ($quoteTransfer->getItems() as $item) {
            $shipmentTransfer = $quoteTransfer->getShipment();
            $shipmentTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
            $item->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }
}
