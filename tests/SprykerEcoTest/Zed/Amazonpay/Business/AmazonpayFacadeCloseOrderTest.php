<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;

class AmazonpayFacadeCloseOrderTest extends AmazonpayFacadeAbstractTest
{
    /**
     * @dataProvider closeOrderDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $orderTransfer
     *
     * @return void
     */
    public function testCloseOrder(AmazonpayCallTransfer $orderTransfer)
    {
        $result = $this->createFacade()->closeOrder($orderTransfer);
        $this->validateResult($result, AmazonpayConfig::OMS_STATUS_CLOSED);
    }

    /**
     * @return array
     */
    public function closeOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            [$this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1)],
            [$this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2)],
            [$this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3)],
        ];
    }
}
