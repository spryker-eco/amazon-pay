<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeSaveOrderPaymentTest extends AmazonpayFacadeAbstractTest
{
    const ITEMS_COUNT = 5;
    const SELLER_REFERENCE_ID = 'seller-reference-id';

    /**
     * @dataProvider providerQuotes
     *
     * @param string $orderReference
     * @param string $idsList
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsState
     * @param string $expectedPaymentStatus
     *
     * @return void
     */
    public function testSaveOrderPayment($orderReference, $idsList, AmazonpayStatusTransfer $status, SpyOmsOrderItemState $omsState, $expectedPaymentStatus)
    {
        $quote = $this->createQuote($orderReference, $idsList, $status, $omsState);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setSaveOrder(
            (new SaveOrderTransfer())
            ->setOrderItems($quote->getItems())
        );

        $this->createFacade()->saveOrderPayment($quote, $checkoutResponseTransfer);

        $itemIds = [];

        foreach ($quote->getItems() as $item) {
            $itemIds[$item->getIdSalesOrderItem()] = 1;
        }

        $paymentEntity = $this->getAmazonPayPayment($quote->getOrderReference());
        $this->assertEquals(self::SELLER_REFERENCE_ID, $paymentEntity->getSellerOrderId());
        $this->assertEquals($expectedPaymentStatus, $paymentEntity->getStatus());

        foreach ($paymentEntity->getSpyPaymentAmazonpaySalesOrderItems() as $amazonpaySalesOrderItem) {
            $this->assertArrayHasKey($amazonpaySalesOrderItem->getFkSalesOrderItem(), $itemIds);
        }

        $this->assertCount(self::ITEMS_COUNT, $paymentEntity->getSpyPaymentAmazonpaySalesOrderItems());
    }

    /**
     * @return void
     */
    public function testSaveOrderPaymentNonAmazon()
    {
        $quote = new QuoteTransfer();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setSaveOrder(
            (new SaveOrderTransfer())
            ->setOrderItems($quote->getItems())
        );

        $this->createFacade()->saveOrderPayment($quote, $checkoutResponseTransfer);
        $this->assertTrue(true);
    }

    /**
     * @return array
     */
    public function providerQuotes()
    {
        $omsState = (new SpyOmsOrderItemStateQuery())
            ->filterByName('new')
            ->findOneOrCreate();
        $omsState->save();

        return [
            [
                AbstractResponse::ORDER_REFERENCE_ID_1 . 'save',
                '',
                (new AmazonpayStatusTransfer())->setState(AmazonPayConfig::STATUS_DECLINED),
                $omsState,
                AmazonPayConfig::STATUS_DECLINED,
            ],
            [
                AbstractResponse::ORDER_REFERENCE_ID_2 . 'save',
                '',
                (new AmazonpayStatusTransfer())->setState(AmazonPayConfig::STATUS_PENDING),
                $omsState,
                AmazonPayConfig::STATUS_PENDING,
            ],
            [
                AbstractResponse::ORDER_REFERENCE_ID_3 . 'save',
                '',
                (new AmazonpayStatusTransfer())->setState(AmazonPayConfig::STATUS_OPEN),
                $omsState,
                AmazonPayConfig::STATUS_OPEN,
            ],
            [
                AbstractResponse::ORDER_REFERENCE_ID_4 . 'save',
                '1,',
                (new AmazonpayStatusTransfer())->setState(AmazonPayConfig::STATUS_DECLINED),
                $omsState,
                AmazonPayConfig::STATUS_COMPLETED,
            ],
            [
                AbstractResponse::ORDER_REFERENCE_ID_5 . 'save',
                '1,',
                (new AmazonpayStatusTransfer())->setState(AmazonPayConfig::STATUS_PENDING),
                $omsState,
                AmazonPayConfig::STATUS_COMPLETED,
            ],
            [
                AbstractResponse::ORDER_REFERENCE_ID_6 . 'save',
                '1,',
                (new AmazonpayStatusTransfer())->setState(AmazonPayConfig::STATUS_OPEN),
                $omsState,
                AmazonPayConfig::STATUS_COMPLETED,
            ],
        ];
    }

    /**
     * @param string $orderReference
     * @param string $idsList
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsState
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote($orderReference, $idsList, AmazonpayStatusTransfer $status, SpyOmsOrderItemState $omsState)
    {
        $salesOrder = $this->createSalesOrder($orderReference);

        $quote = new QuoteTransfer();
        $quote->setOrderReference($orderReference);

        for ($i = 0; $i < self::ITEMS_COUNT; $i++) {
            $item = new SpySalesOrderItem();
            $item->setFkSalesOrder($salesOrder->getIdSalesOrder());
            $item->setFkOmsOrderItemState($omsState->getIdOmsOrderItemState());
            $item->setName($i);
            $item->setSku($i);
            $item->setGrossPrice($i);
            $item->save();

            $quote->addItem(
                (new ItemTransfer())
                    ->setIdSalesOrderItem($item->getIdSalesOrderItem())
            );
        }

        $quote->setAmazonpayPayment(
            (new AmazonpayPaymentTransfer())
                ->setIsSandbox(1)
                ->setSellerOrderId(self::SELLER_REFERENCE_ID)
                ->setOrderReferenceId($orderReference)
                ->setResponseHeader(new AmazonpayResponseHeaderTransfer())
                ->setAuthorizationDetails(
                    (new AmazonpayAuthorizationDetailsTransfer())
                        ->setAuthorizationStatus($status)
                        ->setIdList($idsList)
                )
        );

        return $quote;
    }
}
