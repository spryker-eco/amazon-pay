<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeUpdateAuthorizationStatusTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider updateAuthStatusDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testUpdateAuthStatus(AmazonpayCallTransfer $amazonpayCallTransfer, $expectedStatus)
    {
        $result = $this->createFacade()->updateAuthorizationStatus($amazonpayCallTransfer);
        $this->validateResult($result, $expectedStatus);
    }

    /**
     * @return array
     */
    public function updateAuthStatusDataProvider()
    {
        $this->prepareFixtures();

        return [
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                AmazonPayConfig::STATUS_OPEN,
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                AmazonPayConfig::STATUS_CLOSED,
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                AmazonPayConfig::STATUS_SUSPENDED,
            ],
        ];
    }
}
