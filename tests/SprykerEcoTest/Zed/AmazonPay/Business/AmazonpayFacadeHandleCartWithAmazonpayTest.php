<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\ClientMock;

class AmazonpayFacadeHandleCartWithAmazonpayTest extends AmazonpayFacadeAbstractTest
{
    const ID_CUSTOMER = 12;
    const GRAND_TOTAL = 1000;

    /**
     * @param bool $loggedIn
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote($loggedIn)
    {
        $quote = new QuoteTransfer();
        if ($loggedIn) {
            $quote->setCustomer(new CustomerTransfer());
            $quote->getCustomer()->setIdCustomer(self::ID_CUSTOMER);
        }
        $quote->setAmazonpayPayment(new AmazonpayPaymentTransfer());
        $quote->setTotals(
            (new TotalsTransfer())
            ->setGrandTotal(self::GRAND_TOTAL)
        );

        return $quote;
    }

    /**
     * @dataProvider handleCartDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $loggedIn
     *
     * @return void
     */
    public function testHandleCartWithAmazonpay(QuoteTransfer $quoteTransfer, $loggedIn)
    {
        $resultTransfer = $this->createFacade()->handleCartWithAmazonPay($quoteTransfer);

        $this->assertNotNull($resultTransfer->getCustomer());
        if ($loggedIn) {
            $this->assertEquals(self::ID_CUSTOMER, $resultTransfer->getCustomer()->getIdCustomer());
            $this->assertEquals($quoteTransfer->getCustomer()->getFirstName(), $resultTransfer->getCustomer()->getFirstName());
            $this->assertEquals($quoteTransfer->getCustomer()->getLastName(), $resultTransfer->getCustomer()->getLastName());
            $this->assertEquals($quoteTransfer->getCustomer()->getEmail(), $resultTransfer->getCustomer()->getEmail());
        } else {
            $this->assertEquals(ClientMock::FIRST_NAME, $resultTransfer->getCustomer()->getFirstName());
            $this->assertEquals(ClientMock::LAST_NAME, $resultTransfer->getCustomer()->getLastName());
            $this->assertEquals(ClientMock::EMAIL, $resultTransfer->getCustomer()->getEmail());
        }
        $this->assertNotEmpty(
            $resultTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()
        );
        $this->assertNotEmpty(
            $resultTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()
        );
        $this->assertNotEmpty(
            $resultTransfer->getAmazonpayPayment()->getRefundDetails()->getRefundStatus()
        );
        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentMethod(),
            AmazonPayConfig::PROVIDER_NAME
        );
        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentProvider(),
            AmazonPayConfig::PROVIDER_NAME
        );
        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentSelection(),
            AmazonPayConfig::PROVIDER_NAME
        );
    }

    /**
     * @return array
     */
    public function handleCartDataProvider()
    {
        return [
            'Logged in user' => [
                $this->createQuote(true), true,
            ],
            'Anonymous user' => [
                $this->createQuote(false), false,
            ],
        ];
    }
}
