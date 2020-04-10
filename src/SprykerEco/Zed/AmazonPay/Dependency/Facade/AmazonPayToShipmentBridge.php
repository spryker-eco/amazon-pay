<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use RuntimeException;

class AmazonPayToShipmentBridge implements AmazonPayToShipmentInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableShipmentMethods(QuoteTransfer $quoteTransfer)
    {
        if (method_exists($this->shipmentFacade, 'getAvailableMethodsByShipment') === true) {
            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setShipment(new ShipmentTransfer());
                $itemTransfer->getShipment()->setShippingAddress(new AddressTransfer());
            }

            $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);

            if ($shipmentMethodsCollectionTransfer->getShipmentMethods()->count() > 1) {
                throw new RuntimeException('Split shipping is not supported');
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setShipment(null);
            }

            $shipmentMethodsTransfer = $shipmentMethodsCollectionTransfer->getShipmentMethods()->getIterator()
                ->current();

            return $shipmentMethodsTransfer;
        }

        return $this->shipmentFacade->getAvailableMethods($quoteTransfer);
    }
}
