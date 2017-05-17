<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock;


use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer;

class AmazonpayQueryContainerMock extends AmazonpayQueryContainer
{

    /**
     * @param string $orderReferenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    public function retrievePaymentByOrderReferenceId($orderReferenceId)
    {
        $payment = new SpyPaymentAmazonpay();
        $payment->setOrderReferenceId($orderReferenceId);

        switch ($orderReferenceId) {
            case 'S02-1234567-0000001': ;

            case 'S02-1234567-0000002': ;
            case 'S02-1234567-0000003': ;
        }
    }

}
