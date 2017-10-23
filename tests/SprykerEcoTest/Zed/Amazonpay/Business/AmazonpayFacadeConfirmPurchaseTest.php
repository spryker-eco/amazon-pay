<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock;
use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeConfirmPurchaseTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @param string $orderReferenceId
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote($orderReferenceId)
    {
        $quote = new QuoteTransfer();

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(50000);
        $quote->setTotals($totals);

        $amazonpayTransfer = new AmazonpayPaymentTransfer();
        $amazonpayTransfer->setOrderReferenceId($orderReferenceId);
        $amazonpayTransfer->setAddressConsentToken('addressconsenttoken');
        $amazonpayTransfer->setSellerOrderId('S02-4691938-4240727591437cc0ccbd');

        $authDetailsTransfer = new AmazonpayAuthorizationDetailsTransfer();
        $statusTransfer = new AmazonpayStatusTransfer();
        $authDetailsTransfer->setAuthorizationStatus($statusTransfer);
        $amazonpayTransfer->setAuthorizationDetails($authDetailsTransfer);

        $quote->setAmazonpayPayment($amazonpayTransfer);

        return $quote;
    }

    /**
     * @dataProvider confirmPurchaseProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $transactionTimeout
     * @param bool $captureNow
     * @param string $authStatus
     * @param string $reasonCode
     * @param string $captureId
     *
     * @return void
     */
    public function testConfirmPurchase(QuoteTransfer $quoteTransfer, $transactionTimeout, $captureNow, $authStatus, $reasonCode, $captureId)
    {
        $additionalConfig = [
            AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT => $transactionTimeout,
            AmazonpayConstants::CAPTURE_NOW => $captureNow,
        ];

        $facade = new AmazonpayFacadeMock($additionalConfig);
        $resultQuote = $facade->confirmPurchase($quoteTransfer);

        $this->assertEquals(
            $authStatus,
            $resultQuote->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()->getState()
        );

        $this->assertEquals(
            $reasonCode,
            $resultQuote->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()->getReasonCode()
        );

        $this->assertEquals(
            $captureId,
            $resultQuote->getAmazonpayPayment()->getAuthorizationDetails()->getIdList()
        );
    }

    /**
     * @return array
     */
    public function confirmPurchaseProvider()
    {
        return [
            'Correct order sync captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 0, true, 'Closed', 'MaxCapturesProcessed', 'S02-6182376-4189497-C003388'],
            'AmazonRejected order sync captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 0, true, 'Declined', 'AmazonRejected', ''],
            'InvalidPaymentMethod order sync captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 0, true, 'Declined', 'InvalidPaymentMethod', ''],

            'Correct order async captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 1000, true, 'Pending', '', 'S02-6182376-4189497-C003388'],
            'AmazonRejected order async captureNow on ' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 1000, true, 'Pending', '', 'S02-6182376-4189497-C003388'],
            'InvalidPaymentMethod order async captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 1000, true, 'Pending', '', 'S02-6182376-4189497-C003388'],

            'Correct order sync captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 0, false, 'Open', '', ''],
            'AmazonRejected order sync captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 0, false, 'Declined', 'AmazonRejected', ''],
            'InvalidPaymentMethod order sync captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 0, false, 'Declined', 'InvalidPaymentMethod', ''],

            'Correct order async captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 1000, false, 'Pending', '', ''],
            'AmazonRejected order async captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 1000, false, 'Pending', '', ''],
            'InvalidPaymentMethod order async captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 1000, false, 'Pending', '', ''],
        ];
    }

}
