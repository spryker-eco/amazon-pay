<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayIpnPaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\AmazonpayIpnRequestMessageTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeHandleAmazonpayIpnRequestTest extends AmazonpayFacadeAbstractTest
{

    const REFERENCE_1 = 'asdasd-asdasd-asdasd';

    /**
     * @dataProvider updateRefundStatusDataProvider
     *
     * @param AbstractTransfer $transfer
     */
    public function testFacadeHandleAmazonpayIpnRequest(AbstractTransfer $transfer)
    {
        $this->createFacade()->handleAmazonpayIpnRequest($transfer);

        $this->getP
    }

    /**
     * @return array
     */
    public function updateRefundStatusDataProvider()
    {
        return [
            [
                self::REFERENCE_1,
                $this->creataAmazonpayIpnPaymentAuthorizeRequestTransfer(self::REFERENCE_1),
                AmazonpayConstants::OMS_STATUS_AUTH_DECLINED,
            ],
        ];
    }

    /**
     * @param string $reference
     *
     * @return TransferInterface
     */
    protected function creataAmazonpayIpnPaymentAuthorizeRequestTransfer($reference)
    {
        return (new AmazonpayIpnPaymentAuthorizeRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                ->setNotificationType(AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE)
            )
            ->setAuthorizationDetails(
                (new AmazonpayAuthorizationDetailsTransfer())
                ->setAuthorizationStatus(
                    (new AmazonpayStatusTransfer())
                    ->setIsDeclined(1)
                )
                ->setAuthorizationReferenceId($reference)
            );
    }

}
