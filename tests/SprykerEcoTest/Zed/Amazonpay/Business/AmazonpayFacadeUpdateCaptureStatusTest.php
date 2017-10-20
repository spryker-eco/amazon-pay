<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeUpdateCaptureStatusTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider updateCaptureStatusDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testUpdateCaptureStatus(AmazonpayCallTransfer $amazonpayCallTransfer, $expectedStatus)
    {
        $result = $this->createFacade()->updateCaptureStatus($amazonpayCallTransfer);
        $this->validateResult($result, $expectedStatus);
    }

    /**
     * @return array
     */
    public function updateCaptureStatusDataProvider()
    {
        $this->prepareFixtures();

        return [
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED,
            ],
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED,
            ],
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING,
            ],
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_4),
                AmazonpayConstants::OMS_STATUS_CAPTURE_CLOSED,
            ],
        ];
    }

}
