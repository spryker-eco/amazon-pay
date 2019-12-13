<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

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
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableShipmentMethods(QuoteTransfer $quoteTransfer)
    {

        if (method_exists($this->shipmentFacade, 'getAvailableMethodsByShipment') === true) {

            $shipmentMethodsCollectionTransfer = $this->shipmentFacade->getAvailableMethodsByShipment($quoteTransfer);

            if ($shipmentMethodsCollectionTransfer->getShipmentMethods()->count() > 0) {
                throw new \Exception('Split shipping is not supported');
            }

            $shipmentMethodsTransfer = $shipmentMethodsCollectionTransfer->getShipmentMethods()->getIterator()
                ->current();

            return  $shipmentMethodsTransfer;
        }

        return $this->shipmentFacade->getAvailableMethods($quoteTransfer);
    }
}
