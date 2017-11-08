<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeReauthorizeExpiredOrderTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider reauthorizeExpiredOrderProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testReauthorizeExpiredOrderTest(AmazonpayCallTransfer $transfer, $expectedStatus)
    {
        $result = $this->createFacade()->reauthorizeExpiredOrder($transfer);

        $payment = $this->getAmazonPayPayment($result->getAmazonpayPayment()->getOrderReferenceId());

        $this->assertEquals($expectedStatus, $payment->getStatus());
    }

    /**
     * @return array
     */
    public function reauthorizeExpiredOrderProvider()
    {
        $this->prepareFixtures();

        return [
            'opened' => [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonPayConfig::STATUS_OPEN,
            ],
            'declined' => [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonPayConfig::STATUS_CLOSED,
            ],
            'suspended' => [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonPayConfig::STATUS_SUSPENDED,
            ],
        ];
    }
}
