<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;

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
        $this->validateResult($result, AmazonpayConfig::OMS_STATUS_AUTH_PENDING);
    }

    /**
     * @return array
     */
    public function cancelOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
            ],
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
            ],
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
            ],
            [
                $this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_4),
            ],
        ];
    }
}
