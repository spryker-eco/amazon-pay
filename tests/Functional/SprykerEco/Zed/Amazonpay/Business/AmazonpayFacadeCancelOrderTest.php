<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeCancelOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider cancelOrderDataProvider
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return void
     */
    public function testCancelOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->createFacade()->cancelOrder($amazonpayCallTransfer);
        $this->validateResult($result, AmazonpayConstants::OMS_STATUS_CANCELLED);
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
        ];
    }

}
