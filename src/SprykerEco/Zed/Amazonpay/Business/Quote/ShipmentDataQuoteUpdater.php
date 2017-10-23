<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface;

class ShipmentDataQuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface $shipmentFacade
     */
    public function __construct(AmazonpayToShipmentInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodTransfer = $this->shipmentFacade->getShipmentMethodTransferById(
            (int)$quoteTransfer->getShipment()->getShipmentSelection()
        );

        $quoteTransfer->getShipment()->setMethod($shipmentMethodTransfer);
        $quoteTransfer = $this->updateExpenses($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateExpenses(QuoteTransfer $quoteTransfer)
    {
        $expenseTransfer = $this->createShippingExpenseTransfer($quoteTransfer->getShipment()->getMethod());

        $otherExpenseCollection = new ArrayObject();
        foreach ($quoteTransfer->getExpenses() as $expense) {
            if ($expense->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                $otherExpenseCollection->append($expense);
            }
        }

        $quoteTransfer->setExpenses($otherExpenseCollection);
        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $shipmentExpenseTransfer = new ExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $shipmentExpenseTransfer->setUnitGrossPrice($shipmentMethodTransfer->getDefaultPrice());
        $shipmentExpenseTransfer->setQuantity(1);

        return $shipmentExpenseTransfer;
    }
}
