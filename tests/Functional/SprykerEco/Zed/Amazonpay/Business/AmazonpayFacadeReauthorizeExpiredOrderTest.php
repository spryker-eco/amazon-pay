<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeReauthorizeExpiredOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider reauthorizeExpiredOrderProvider
     *
     */
    public function testReauthorizeExpiredOrderTest(OrderTransfer $orderTransfer)
    {
        $this->createFacade()->reauthorizeExpiredOrder($orderTransfer);
    }

    /**
     * @return array
     */
    public function reauthorizeExpiredOrderProvider()
    {
        return [
            'first' =>
                [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_FIRST),
                    'S02-5989383-0864061-0000AR1',
                    'S02-5989383-0864061-0000RR1',
                ],
            'second' =>
                [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_SECOND),
                    'S02-5989383-0864061-0000AR2',
                    'S02-5989383-0864061-0000RR2',
                ],
            'third' =>
                [$this->getOrderTransfer(AbstractResponse::ORDER_REFERENCE_ID_THIRD),
                    'S02-5989383-0864061-0000AR3',
                    'S02-5989383-0864061-0000RR3',
                ],
        ];
    }


}
