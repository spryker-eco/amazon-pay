<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class AmazonpayFacadeAddSelectedShipmentMethodToQuoteTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote($shipmentSelection)
    {
        $quote = new QuoteTransfer();

        $shipment = new ShipmentTransfer();
        $shipment->setShipmentSelection($shipmentSelection);
        $quote->setShipment($shipment);

        return $quote;
    }

    /**
     * @dataProvider addSelectedShipmentMethodToQuoteProvider
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $shipmentMethodName
     * @param int $shipmentPrice
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
        return [
            'Standard delivery' => [$this->createQuote(1), 'Standard', 490],
            'Express delivery' => [$this->createQuote(2), 'Express', 590],
        ];
    }
}
