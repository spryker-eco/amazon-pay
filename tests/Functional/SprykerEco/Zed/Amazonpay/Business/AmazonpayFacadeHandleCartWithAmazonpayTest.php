<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

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
            $resultTransfer->getPayment()->getPaymentMethod(),
            AmazonpayConstants::PAYMENT_METHOD
        );
        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentProvider(),
            AmazonpayConstants::PAYMENT_METHOD
        );
        $this->assertEquals(
            $resultTransfer->getPayment()->getPaymentSelection(),
            AmazonpayConstants::PAYMENT_METHOD
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
