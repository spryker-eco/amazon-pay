<?php

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Codeception\TestCase\Test;
use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeHandleCartWithAmazonpayTest extends Test
{

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade
     */
    protected function createFacade()
    {
        return new AmazonpayFacadeMock();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote()
    {
        $quote = new QuoteTransfer();
        $quote->setAmazonpayPayment(new AmazonpayPaymentTransfer());

        return $quote;
    }

    /**
     * @dataProvider handleCartDataProvider
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function testHandleCartWithAmazonpay(QuoteTransfer $quoteTransfer)
    {
        $resultTransfer = $this->createFacade()->handleCartWithAmazonpay($quoteTransfer);
        $this->assertNotEmpty(
            $resultTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()
        );

        $this->assertNotEmpty(
            $resultTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()
        );

        $this->assertNotEmpty(
            $resultTransfer->getAmazonpayPayment()->getRefundDetails()->getRefundStatus()
        );

        $this->assertEquals('john@doe.xxx', $resultTransfer->getCustomer()->getEmail());
        $this->assertEquals('John', $resultTransfer->getCustomer()->getFirstName());
        $this->assertEquals('Doe', $resultTransfer->getCustomer()->getLastName());

        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentMethod(), AmazonpayConstants::PAYMENT_METHOD
        );

        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentProvider(), AmazonpayConstants::PAYMENT_METHOD
        );

        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentSelection(), AmazonpayConstants::PAYMENT_METHOD
        );

        $this->assertNotEmpty($resultTransfer->getShipment()->getMethod());
    }

    /**
     * @return array
     */
    public function handleCartDataProvider()
    {
        return [
            [$this->createQuote()]
        ];
    }

}