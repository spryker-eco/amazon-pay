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
use Pyz\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

/**
 * @group AmazonpayFacadeAddSelectedShipmentMethodToQuoteTest
 */
class AmazonpayFacadeAddSelectedShipmentMethodToQuoteTest extends AmazonpayFacadeAbstractTest
{
    use DependencyHelperTrait;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->setDependency(
            ShipmentDependencyProvider::SHIPMENT_METHOD_FILTER_PLUGINS,
            function () {
                return [];
            }
        );
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
        $shipmentMethodId = $this->createShipmentMethod($shipmentMethodName, $shipmentPrice);
        $quoteTransfer->getShipment()->setShipmentSelection($shipmentMethodId);

        $resultQuote = $this->createFacade()->addSelectedShipmentMethodToQuote($quoteTransfer);

        /** @var \Generated\Shared\Transfer\ExpenseTransfer $expensesTransfer */
        $expensesTransfer = $resultQuote->getExpenses()->getArrayCopy()[0];

        $this->assertEquals($shipmentMethodName, $expensesTransfer->getName());
        $this->assertEquals($shipmentPrice, $expensesTransfer->getUnitGrossPrice());
    }

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
     * @return array
     */
    public function addSelectedShipmentMethodToQuoteProvider()
    {
        return [
            'Standard delivery' => [$this->createQuote(), 'Standard', 490],
            'Express delivery' => [$this->createQuote(), 'Express', 590],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethods = $this->createShipmentFacade()->getAvailableMethods($quoteTransfer);

        return array_map(
            function (ShipmentMethodTransfer $method) {
                return $method->getIdShipmentMethod();
            },
            $shipmentMethods->getMethods()->getArrayCopy()
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected function createShipmentFacade()
    {
        return new ShipmentFacade();
    }
}
