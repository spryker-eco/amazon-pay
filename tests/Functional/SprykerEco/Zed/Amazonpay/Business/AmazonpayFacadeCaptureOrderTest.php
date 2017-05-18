<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeCaptureOrderTest extends AmazonpayFacadeAbstractTest
{

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
            'Completed' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_FIRST), 'Completed'],
            'Pending' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_SECOND), 'Pending'],
            'Declined' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_THIRD), 'Declined'],
        ];
    }

}
