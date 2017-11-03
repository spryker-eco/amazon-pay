<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeRefundOrderTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider refundOrderDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $transfer
     * @param string $refundAmazonpayId
     * @param string $refundReferenceId
     *
     * @return void
     */
    public function testRefundOrder(AmazonpayCallTransfer $transfer, $refundAmazonpayId, $refundReferenceId)
    {
        $this->createFacade()->refundOrder($transfer);

        $updatedTransfer = $this->getAmazonPayCallTransferByOrderReferenceId($transfer->getAmazonpayPayment()->getOrderReferenceId());

        $this->assertEquals(
            AmazonPayConfig::OMS_STATUS_REFUND_PENDING,
            $updatedTransfer->getAmazonpayPayment()->getStatus()
        );

        $this->assertEquals(
            $refundAmazonpayId,
            $updatedTransfer->getAmazonpayPayment()->getRefundDetails()->getAmazonRefundId()
        );

        $this->assertEquals(
            $refundReferenceId,
            $updatedTransfer->getAmazonpayPayment()->getRefundDetails()->getRefundReferenceId()
        );
    }

    /**
     * @return array
     */
    public function refundOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            'first' =>
                [
                    $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                    'S02-5989383-0864061-0000AR1',
                    'S02-5989383-0864061-0000RR1',
                ],
                'second' =>
                [
                    $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                    'S02-5989383-0864061-0000AR2',
                    'S02-5989383-0864061-0000RR2',
                ],
                'third' =>
                [
                    $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                    'S02-5989383-0864061-0000AR3',
                    'S02-5989383-0864061-0000RR3',
                ],
        ];
    }
}
