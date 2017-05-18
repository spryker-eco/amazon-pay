<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeCloseOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider closeOrderDataProvider
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function testCloseOrder(OrderTransfer $orderTransfer)
    {
        $this->createFacade()->closeOrder($orderTransfer);
    }

    /**
     * @return array
     */
    public function closeOrderDataProvider()
    {
        return [
            'Completed' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_FIRST), 'Completed'],
            'Pending' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_SECOND), 'Pending'],
            'Declined' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_THIRD), 'Declined'],
        ];
    }

}
