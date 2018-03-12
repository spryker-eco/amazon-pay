<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface;

class ShipmentDataQuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface $shipmentFacade
     */
    public function __construct(AmazonPayToShipmentInterface $shipmentFacade)
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
        $shipmentMethodTransfer = $this->getShipmentMethodByQuote($quoteTransfer);

        $quoteTransfer->getShipment()->setMethod($shipmentMethodTransfer);
        $quoteTransfer = $this->updateExpenses($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function getShipmentMethodByQuote(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodsTransfer = $this->getAvailableShipmentMethods($quoteTransfer);
        $idShipmentMethod = (int)$quoteTransfer->getShipment()->getShipmentSelection();

        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        throw new Exception(sprintf('Shipment method #%s was not found , %s', $idShipmentMethod, json_encode($shipmentMethodsTransfer->toArray())));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableShipmentMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->shipmentFacade->getAvailableShipmentMethods($quoteTransfer);
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
        $shipmentExpenseTransfer->setUnitGrossPrice($shipmentMethodTransfer->getStoreCurrencyPrice());
        $shipmentExpenseTransfer->setQuantity(1);

        return $shipmentExpenseTransfer;
    }
}
