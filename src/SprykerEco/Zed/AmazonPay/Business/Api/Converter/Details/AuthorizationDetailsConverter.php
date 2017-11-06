<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\AbstractArrayConverter;

class AuthorizationDetailsConverter extends AbstractArrayConverter
{
    const PAYMENT_METHOD_INVALID = 'InvalidPaymentMethod';
    const CAPTURE_NOW = 'CaptureNow';
    const CAPTURED_AMOUNT = 'CapturedAmount';
    const SELLER_AUTHORIZATION_NOTE = 'SellerAuthorizationNote';
    const AUTHORIZATION_FEE = 'AuthorizationFee';
    const AUTHORIZATION_STATUS = 'AuthorizationStatus';
    const AUTHORIZATION_AMOUNT = 'AuthorizationAmount';
    const SOFT_DECLINE = 'SoftDecline';
    const CREATION_TIMESTAMP = 'CreationTimestamp';
    const ID_LIST = 'IdList';
    const EXPIRATION_TIMESTAMP = 'ExpirationTimestamp';
    const AMAZON_AUTHORIZATION_ID = 'AmazonAuthorizationId';
    const AUTHORIZATION_REFERENCE_ID = 'AuthorizationReferenceId';

    /**
     * @param array $authDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer
     */
    public function convert(array $authDetailsData)
    {
        $authorizationDetails = new AmazonpayAuthorizationDetailsTransfer();

        $this->convertGenericDetails($authDetailsData, $authorizationDetails);
        $this->convertAuthorizationDetails($authDetailsData, $authorizationDetails);
        $this->convertCaptureDetails($authDetailsData, $authorizationDetails);

        return $authorizationDetails;
    }

    /**
     * @param array $authDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function convertGenericDetails(array $authDetailsData, AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        if (!empty($authDetailsData[static::EXPIRATION_TIMESTAMP])) {
            $authorizationDetails->setExpirationTimestamp($authDetailsData[static::EXPIRATION_TIMESTAMP]);
        }

        if (!empty($authDetailsData[static::ID_LIST])) {
            $authorizationDetails->setIdList(array_values($authDetailsData[static::ID_LIST])[0]);
        }

        if (!empty($authDetailsData[static::SOFT_DECLINE])) {
            $authorizationDetails->setSoftDecline($authDetailsData[static::SOFT_DECLINE]);
        }

        if (!empty($authDetailsData[static::CREATION_TIMESTAMP])) {
            $authorizationDetails->setCreationTimestamp($authDetailsData[static::CREATION_TIMESTAMP]);
        }
    }

    /**
     * @param array $authDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function convertAuthorizationDetails(array $authDetailsData, AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        $authorizationDetails->setAmazonAuthorizationId($authDetailsData[static::AMAZON_AUTHORIZATION_ID]);
        $authorizationDetails->setAuthorizationReferenceId($authDetailsData[static::AUTHORIZATION_REFERENCE_ID]);

        if (!empty($authDetailsData[static::AUTHORIZATION_AMOUNT])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData[static::AUTHORIZATION_AMOUNT]));
        }

        if (!empty($authDetailsData[static::AUTHORIZATION_FEE])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData[static::AUTHORIZATION_FEE]));
        }

        if (!empty($authDetailsData[static::AUTHORIZATION_STATUS])) {
            $authorizationDetails->setAuthorizationStatus(
                $this->convertStatusToTransfer($authDetailsData[static::AUTHORIZATION_STATUS])
            );
        }

        if (!empty($authDetailsData[static::SELLER_AUTHORIZATION_NOTE])) {
            $authorizationDetails->setSellerAuthorizationNote($authDetailsData[static::SELLER_AUTHORIZATION_NOTE]);
        }
    }

    /**
     * @param array $authDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function convertCaptureDetails(array $authDetailsData, AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        if (!empty($authDetailsData[static::CAPTURED_AMOUNT])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData[static::CAPTURED_AMOUNT]));
        }

        if (!empty($authDetailsData[static::CAPTURE_NOW])) {
            $authorizationDetails->setCaptureNow($authDetailsData[static::CAPTURE_NOW]);
        }
    }
}
