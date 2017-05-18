<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeRefundOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @param string $orderReferenceId
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer($orderReferenceId)
    {
        $orderTransfer = parent::getOrderTransfer($orderReferenceId);
        $orderTransfer->getTotals()->setRefundTotal(200);

        return $orderTransfer;
    }

    /**
     * @dataProvider refundOrderDataProvider
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function testRefundOrder(OrderTransfer $orderTransfer, $status, $refundAmazonpayId, $refundReferenceId)
    {
        $this->createFacade()->refundOrder($orderTransfer);

        $refreshedOrderTransfer = $this->getOrderTransfer($orderTransfer->getAmazonpayPayment()->getOrderReferenceId());
        
        $this->assertEquals(
            AmazonpayConstants::OMS_STATUS_REFUND_PENDING,
            $refreshedOrderTransfer->getAmazonpayPayment()->getStatus()
        );

        $this->assertEquals(
            $refundAmazonpayId,
            $refreshedOrderTransfer->getAmazonpayPayment()->getRefundDetails()->getAmazonRefundId()
        );

        $this->assertEquals(
            $refundReferenceId,
            $refreshedOrderTransfer->getAmazonpayPayment()->getRefundDetails()->getRefundReferenceId()
        );
    }

    /**
     * @return array
     */
    public function refundOrderDataProvider()
    {
        return [
            'first' =>
                [$this->getOrderTransfer('S02-5989383-0864061-0000001'),
                    'refund pendind',
                    'S02-5989383-0864061-0000AR1',
                    'S02-5989383-0864061-0000RR1',
                ],
            'second' =>
                [$this->getOrderTransfer('S02-5989383-0864061-0000002'),
                    'shit happens',
                    'S02-5989383-0864061-0000AR2',
                    'S02-5989383-0864061-0000RR2',
                ],
            'third' =>
                [$this->getOrderTransfer('S02-5989383-0864061-0000003'),
                    'you suck',
                    'S02-5989383-0864061-0000AR3',
                    'S02-5989383-0864061-0000RR3',
                ],
        ];
    }

}