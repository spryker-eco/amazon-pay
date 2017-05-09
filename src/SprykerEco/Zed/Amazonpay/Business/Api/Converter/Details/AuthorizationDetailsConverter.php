<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter;

class AuthorizationDetailsConverter extends AbstractArrayConverter
{

    const PAYMENT_METHOD_INVALID = 'InvalidPaymentMethod';

    /**
     * @param array $authDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer
     */
    public function convert(array $authDetailsData)
    {
        $authorizationDetails = new AmazonpayAuthorizationDetailsTransfer();
        $authorizationDetails->setAmazonAuthorizationId($authDetailsData['AmazonAuthorizationId']);
        $authorizationDetails->setAuthorizationReferenceId($authDetailsData['AuthorizationReferenceId']);

        if (!empty($authDetailsData['AuthorizationAmount'])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData['AuthorizationAmount']));
        }

        if (!empty($authDetailsData['CapturedAmount'])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData['CapturedAmount']));
        }

        if (!empty($authDetailsData['AuthorizationFee'])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData['AuthorizationFee']));
        }

        if (!empty($authDetailsData['AuthorizationStatus'])) {
            $authorizationDetails->setAuthorizationStatus(
                $this->convertStatusToTransfer($authDetailsData['AuthorizationStatus'])
            );
        }

        if (!empty($authDetailsData['ExpirationTimestamp'])) {
            $authorizationDetails->setExpirationTimestamp($authDetailsData['ExpirationTimestamp']);
        }

        if (!empty($authDetailsData['IdList'])) {
            $authorizationDetails->setIdList(array_values($authDetailsData['IdList'])[0]);
        }

        if (!empty($authDetailsData['SoftDecline'])) {
            $authorizationDetails->setSoftDecline($authDetailsData['SoftDecline']);
        }

        if (!empty($authDetailsData['CaptureNow'])) {
            $authorizationDetails->setCaptureNow($authDetailsData['CaptureNow']);
        }

        if (!empty($authDetailsData['SellerAuthorizationNote'])) {
            $authorizationDetails->setSellerAuthorizationNote($authDetailsData['CaptureNow']);
        }

        if (!empty($authDetailsData['CreationTimestamp'])) {
            $authorizationDetails->setCreationTimestamp($authDetailsData['CreationTimestamp']);
        }

        return $authorizationDetails;
    }

}
