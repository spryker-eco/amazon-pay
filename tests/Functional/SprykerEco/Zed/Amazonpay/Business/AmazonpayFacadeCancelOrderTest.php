<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeCancelOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider cancelOrderDataProvider
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function testCancelOrder(OrderTransfer $orderTransfer)
    {
        $this->createFacade()->cancelOrder($orderTransfer);
    }

    /**
     * @param string $orderReferenceId
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer($orderReferenceId)
    {
        $orderTransfer = parent::getOrderTransfer($orderReferenceId);
        $orderTransfer->getTotals()->setRefundTotal(200);

        return $orderTransfer;
    }

    /**
     * @return array
     */
    public function cancelOrderDataProvider()
    {
        return [
            'Completed' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_FIRST), 'Completed'],
            'Pending' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_SECOND), 'Pending'],
            'Declined' => [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_THIRD), 'Declined'],
        ];
    }

}
