<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeUpdateRefundStatusTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider updateRefundStatusDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testUpdateRefundStatus(AmazonpayCallTransfer $amazonpayCallTransfer, $expectedStatus)
    {
        $result = $this->createFacade()->updateRefundStatus($amazonpayCallTransfer);
        $this->validateResult($result, $expectedStatus);
    }

    /**
     * @return array
     */
    public function updateRefundStatusDataProvider()
    {
        $this->prepareFixtures();

        return [
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonPayConfig::STATUS_COMPLETED,
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonPayConfig::STATUS_DECLINED,
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonPayConfig::STATUS_PENDING,
            ],
        ];
    }
}
