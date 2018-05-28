<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface;

class AmazonpayFacadeAddSelectedShipmentMethodToQuoteTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer $shipmentSelection
     */
    protected function createQuote()
    {
        $quote = new QuoteTransfer();
        $quote->setCurrency(
            (new CurrencyTransfer())
                ->setCode('EUR')
        )
            ->setPriceMode(ShipmentConstants::PRICE_MODE_GROSS);

        $shipment = new ShipmentTransfer();
        $quote->setShipment($shipment);

        return $quote;
    }

    /**
     * @dataProvider addSelectedShipmentMethodToQuoteProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $shipmentMethodIndex
     * @param string $shipmentMethodName
     * @param int $shipmentPrice
     *
     * @return void
     */
    public function testAddSelectedShipmentMethodToQuote(QuoteTransfer $quoteTransfer, $shipmentMethodIndex, $shipmentMethodName, $shipmentPrice)
    {
        $this->createShipmentMethods(2);
        $shipmentMethodIds = $this->getAvailableMethods($quoteTransfer);
        $quoteTransfer->getShipment()->setShipmentSelection($shipmentMethodIds[$shipmentMethodIndex]);

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
            'Standard delivery' => [$this->createQuote(), 0, 'Standard', 490],
            'Express delivery' => [$this->createQuote(), 1, 'Express', 590],
        ];
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethods = $this->createShipmentFacade()->getAvailableMethods($quoteTransfer);

        return array_map(
            function(ShipmentMethodTransfer $method) {
                return $method->getIdShipmentMethod();
            },
            $shipmentMethods->getMethods()->getArrayCopy()
        );
    }

    /**
     * @return ShipmentFacadeInterface
     */
    protected function createShipmentFacade()
    {
        return new ShipmentFacade();
    }
}
