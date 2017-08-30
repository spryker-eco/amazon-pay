<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeCaptureOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider captureOrderDataProvider
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $transfer
     * @param string $status
     */
    public function testCaptureOrder(AmazonpayCallTransfer $transfer, $status)
    {
        $result = $this->createFacade()->captureOrder($transfer);
        $this->assertTrue($result->getAmazonpayPayment()->getResponseHeader()->getIsSuccess());

        $this->assertEquals(
            $status,
            $result->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()->getState()
        );
    }

    /**
     * @return array
     */
    public function captureOrderDataProvider()
    {
        return [
            'Completed' => [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                'Completed'
            ],
            'Pending' => [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                'Pending'
            ],
            'Declined' => [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                'Declined'
            ],
        ];
    }

}
