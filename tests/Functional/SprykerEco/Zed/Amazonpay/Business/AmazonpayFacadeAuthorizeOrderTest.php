<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeAuthorizeOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider cancelOrderDataProvider
     *
     * @param int $timeout
     * @param int $captureNow
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testAuthorizeOrder($timeout, $captureNow, AmazonpayCallTransfer $amazonpayCallTransfer, $expectedStatus)
    {
        $result = $this->createFacade([
            AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT => $timeout,
            AmazonpayConstants::CAPTURE_NOW => $captureNow,
        ])->authorizeOrderItems($amazonpayCallTransfer);

        $payment = $this->getAmazonpayPayment($result->getAmazonpayPayment()->getOrderReferenceId());

        $this->assertEquals($expectedStatus, $payment->getStatus());
    }

    /**
     * @return array
     */
    public function cancelOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            [
                1,
                0,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonpayConstants::OMS_STATUS_AUTH_PENDING,
            ],
            [
                1,
                1,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonpayConstants::OMS_STATUS_AUTH_PENDING,
            ],
            [
                0,
                1,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonpayConstants::OMS_STATUS_AUTH_DECLINED,
            ],
            [
                0,
                1,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonpayConstants::OMS_STATUS_AUTH_DECLINED,
            ],
            [
                0,
                1,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED,
            ],
            [
                0,
                0,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonpayConstants::OMS_STATUS_AUTH_OPEN,
            ],
            [
                0,
                0,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonpayConstants::OMS_STATUS_AUTH_DECLINED,
            ],
            [
                0,
                0,
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED,
            ],
        ];
    }

}
