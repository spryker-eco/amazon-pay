<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class AmazonpayFacadeReauthorizeExpiredOrderTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider reauthorizeExpiredOrderProvider
     *
     * @param AmazonpayCallTransfer $transfer
     */
    public function testReauthorizeExpiredOrderTest(AmazonpayCallTransfer $transfer)
    {
        $this->createFacade()->reauthorizeExpiredOrder($transfer);
    }

    /**
     * @return array
     */
    public function reauthorizeExpiredOrderProvider()
    {
        return [
            'first' =>
                [$this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_1),
                    'S02-5989383-0864061-0000AR1',
                    'S02-5989383-0864061-0000RR1',
                ],
            'second' =>
                [$this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_2),
                    'S02-5989383-0864061-0000AR2',
                    'S02-5989383-0864061-0000RR2',
                ],
            'third' =>
                [$this->getAmazonpayCallTransferByOrderReferenceId(AbstractResponse::ORDER_REFERENCE_ID_3),
                    'S02-5989383-0864061-0000AR3',
                    'S02-5989383-0864061-0000RR3',
                ],
        ];
    }


}
