<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeReauthorizeOrderTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider cancelOrderDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return void
     */
    public function testAuthorizeOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->createFacade()->reauthorizeSuspendedOrder($amazonpayCallTransfer);
        $this->validateResult($result, AmazonPayConfig::STATUS_PENDING);
    }

    /**
     * @return array
     */
    public function cancelOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
            ],
            [
                $this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_4),
            ],
        ];
    }
}
