<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

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
            'Completed' => [$this->getOrderTransfer('S02-5989383-0864061-0000001'), 'Completed'],
            'Pending' => [$this->getOrderTransfer('S02-5989383-0864061-0000002'), 'Pending'],
            'Declined' => [$this->getOrderTransfer('S02-5989383-0864061-0000003'), 'Declined'],
        ];
    }

}
