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

class AmazonpayFacadeHandleCartWithAmazonpayTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @param int $total
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote($total = 0)
    {
        $quote = new QuoteTransfer();
        $quote->setCustomer(new CustomerTransfer());
        $quote->setAmazonpayPayment(new AmazonpayPaymentTransfer());
        $quote->setTotals(
            (new TotalsTransfer())
            ->setGrandTotal($total)
        );

        return $quote;
    }

    /**
     * @dataProvider handleCartDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testHandleCartWithAmazonpay(QuoteTransfer $quoteTransfer)
    {
        $resultTransfer = $this->createFacade()->handleCartWithAmazonPay($quoteTransfer);

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
            [
                $this->createQuote(),
            ],
        ];
    }
}
