<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface;

class QuoteUpdateFactory implements QuoteUpdateFactoryInterface
{
    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface
     */
    protected $adapterFactory;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface $shipmentFacade
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerInterface $messengerFacade
     */
    public function __construct(
        AdapterFactoryInterface $adapterFactory,
        AmazonpayToShipmentInterface $shipmentFacade,
        AmazonpayToMessengerInterface $messengerFacade
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->shipmentFacade = $shipmentFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater()
    {
        return new ShippingAddressDataQuoteUpdater(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteDataInitializer()
    {
        return new PrepareQuoteCollection(
            $this->messengerFacade,
            [
                $this->createAmazonpayDataQuoteInitializer(),
                $this->createCustomerDataQuoteUpdater(),
                $this->createShipmentDataQuoteInitializer(),
                $this->createPaymentDataQuoteUpdater(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createAmazonpayDataQuoteInitializer()
    {
        return new AmazonpayDataQuoteInitializer();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteInitializer()
    {
        return new ShipmentDataQuoteInitializer();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater()
    {
        return new ShipmentDataQuoteUpdater(
            $this->shipmentFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createCustomerDataQuoteUpdater()
    {
        return new CustomerDataQuoteUpdater(
            $this->adapterFactory->createObtainProfileInformationAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createPaymentDataQuoteUpdater()
    {
        return new PaymentDataQuoteUpdater();
    }
}
