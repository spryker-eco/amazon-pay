<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConstants;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\AmazonPayFacadeMock;

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
     * @param string $expectedStatus
     * @param string $reasonCode
     * @param string $captureId
     *
     * @return void
     */
    public function testConfirmPurchase(QuoteTransfer $quoteTransfer, $transactionTimeout, $captureNow, $expectedStatus, $reasonCode, $captureId)
    {
        $additionalConfig = [
            AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT => $transactionTimeout,
            AmazonPayConstants::CAPTURE_NOW => $captureNow,
            AmazonPayConstants::SUCCESS_PAYMENT_URL => 'successurl',
            AmazonPayConstants::FAILURE_PAYMENT_URL => 'failureurl',
        ];

        $facade = new AmazonPayFacadeMock($additionalConfig);
        $resultQuote = $facade->confirmPurchase($quoteTransfer);


        $amazonPayCallTransfer = $facade->getFactory()->createAmazonpayConverter()->mapToAmazonpayCallTransfer($resultQuote);
        $amazonPayCallTransfer = $facade->authorizeOrderItems($amazonPayCallTransfer);

        $this->assertEquals(
            $expectedStatus,
            $amazonPayCallTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()->getState()
        );
        $this->assertEquals(
            $reasonCode,
            $amazonPayCallTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()->getReasonCode()
        );
        $this->assertEquals(
            $captureId,
            $amazonPayCallTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getIdList()
        );
    }

    /**
     * @return array
     */
    public function confirmPurchaseProvider()
    {
        return [
            'Correct order sync captureNow on logged in user' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 0, true, AmazonPayConfig::STATUS_CLOSED, 'MaxCapturesProcessed', 'S02-6182376-4189497-C003388'],
            'Correct order sync captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 0, true, AmazonPayConfig::STATUS_CLOSED, 'MaxCapturesProcessed', 'S02-6182376-4189497-C003388'],
            'AmazonRejected order sync captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 0, true, AmazonPayConfig::STATUS_DECLINED, 'AmazonRejected', ''],
            'InvalidPaymentMethod order sync captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 0, true, AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID, 'InvalidPaymentMethod', ''],

            'Correct order async captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 1000, true, AmazonPayConfig::STATUS_PENDING, '', 'S02-6182376-4189497-C003388'],
            'AmazonRejected order async captureNow on ' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 1000, true, AmazonPayConfig::STATUS_PENDING, '', 'S02-6182376-4189497-C003388'],
            'InvalidPaymentMethod order async captureNow on' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 1000, true, AmazonPayConfig::STATUS_PENDING, '', 'S02-6182376-4189497-C003388'],

            'Correct order sync captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 0, false, AmazonPayConfig::STATUS_OPEN, '', ''],
            'AmazonRejected order sync captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 0, false, AmazonPayConfig::STATUS_DECLINED, 'AmazonRejected', ''],
            'InvalidPaymentMethod order sync captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 0, false, AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID, 'InvalidPaymentMethod', ''],

            'Correct order async captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_1), 1000, false, AmazonPayConfig::STATUS_PENDING, '', ''],
            'AmazonRejected order async captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_2), 1000, false, AmazonPayConfig::STATUS_PENDING, '', ''],
            'InvalidPaymentMethod order async captureNow off' =>
                [$this->createQuote(AbstractResponse::ORDER_REFERENCE_ID_3), 1000, false, AmazonPayConfig::STATUS_PENDING, '', ''],
        ];
    }
}
