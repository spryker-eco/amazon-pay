<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

class AmazonpayFacadeAddSelectedShipmentMethodToQuoteTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @param int $shipmentSelection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $shipmentSelection
     */
    protected function createQuote($shipmentSelection)
    {
        $quote = new QuoteTransfer();
        $quote->setCurrency(
            (new CurrencyTransfer())
                ->setCode('EUR')
        )
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS);

        $shipment = new ShipmentTransfer();
        $shipment->setShipmentSelection($shipmentSelection);
        $quote->setShipment($shipment);

        return $quote;
    }

    /**
     * @dataProvider addSelectedShipmentMethodToQuoteProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $shipmentMethodName
     * @param int $shipmentPrice
     *
     * @return void
     */
    public function testAddSelectedShipmentMethodToQuote(QuoteTransfer $quoteTransfer, $shipmentMethodName, $shipmentPrice)
    {
        $resultQuote = $this->createFacade()->addSelectedShipmentMethodToQuote($quoteTransfer);

        /** @var \Generated\Shared\Transfer\ExpenseTransfer $expensesTransfer */
        $expensesTransfer = $resultQuote->getExpenses()->getArrayCopy()[0];

        $this->assertEquals($shipmentMethodName, $expensesTransfer->getName());
        $this->assertEquals($shipmentPrice, $expensesTransfer->getUnitGrossPrice());
    }

    /**
     * @return array
     */
    public function addSelectedShipmentMethodToQuoteProvider()
    {
        $shipmentMethodIds = $this->createShipmentMethods(2);

        return [
            'Standard delivery' => [$this->createQuote($shipmentMethodIds[0]), 'Standard', 490],
            'Express delivery' => [$this->createQuote($shipmentMethodIds[1]), 'Express', 590],
        ];
    }
}
