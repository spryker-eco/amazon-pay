<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;

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
        $this->validateResult($result, AmazonPayConfig::OMS_STATUS_CLOSED);
    }

    /**
     * @return array
     */
    public function closeOrderDataProvider()
    {
        $this->prepareFixtures();

        return [
            [$this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1)],
            [$this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2)],
            [$this->getAmazonPayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3)],
        ];
    }
}
