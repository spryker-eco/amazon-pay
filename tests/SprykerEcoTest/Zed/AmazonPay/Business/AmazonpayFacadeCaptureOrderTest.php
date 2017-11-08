<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeCaptureOrderTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider captureOrderDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testCaptureOrder(AmazonpayCallTransfer $transfer, $expectedStatus)
    {
        $result = $this->createFacade()->captureOrder($transfer);
        $this->assertTrue($result->getAmazonpayPayment()->getResponseHeader()->getIsSuccess());

        $this->assertEquals(
            $expectedStatus,
            $result->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus()->getState()
        );
    }

    /**
     * @return array
     */
    public function captureOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            'Completed' => [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonPayConfig::STATUS_COMPLETED,
            ],
            'Declined' => [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonPayConfig::STATUS_DECLINED,
            ],
            'Pending' => [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonPayConfig::STATUS_PENDING,
            ],
        ];
    }
}
