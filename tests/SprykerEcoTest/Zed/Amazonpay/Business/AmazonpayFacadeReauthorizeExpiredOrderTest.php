<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;

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

        $payment = $this->getAmazonpayPayment($result->getAmazonpayPayment()->getOrderReferenceId());

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
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonpayConfig::OMS_STATUS_AUTH_OPEN,
            ],
            'declined' => [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonpayConfig::OMS_STATUS_AUTH_CLOSED,
            ],
            'suspended' => [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonpayConfig::OMS_STATUS_AUTH_SUSPENDED,
            ],
        ];
    }
}
