<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeCaptureOrderTest extends AmazonpayFacadeAbstractTest
{

    protected function _before()
    {
        parent::_before();
    }

    /**
     * @dataProvider captureOrderDataProvider
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $status
     */
    public function testCaptureOrder(OrderTransfer $orderTransfer, $status)
    {
        $resultOrder = $this->createFacade()->captureOrder($orderTransfer);

        $this->assertEquals(
            $status,
            $resultOrder->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()->getState()
        );
    }

    /**
     * @return array
     */
    public function captureOrderDataProvider()
    {
        return [
            'Completed' => [$this->getOrderTransfer('S02-5989383-0864061-0000001'), 'Completed'],
            'Pending' => [$this->getOrderTransfer('S02-5989383-0864061-0000002'), 'Pending'],
            'Declined' => [$this->getOrderTransfer('S02-5989383-0864061-0000003'), 'Declined'],
        ];
    }

}
