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
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return void
     */
    public function testAuthorizeOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->createFacade()->authorizeOrderItems($amazonpayCallTransfer);
        $this->validateResult($result, AmazonpayConstants::OMS_STATUS_AUTH_PENDING);
    }

    /**
     * @return array
     */
    public function cancelOrderDataProvider()
    {
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
