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

        $authorizationDetails->fromArray($authDetailsData, true);
        $this->hydrateIdList($authDetailsData, $authorizationDetails);
        $this->hydrateAuthorizationStatus($authDetailsData, $authorizationDetails);
        $this->hydrateCapturedAmount($authDetailsData, $authorizationDetails);
        $this->hydrateReasonCode($authorizationDetails);

        return $authorizationDetails;
    }

    /**
     * @param array $authDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function hydrateIdList(array $authDetailsData, AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        if (!empty($authDetailsData[static::ID_LIST])) {
            $authorizationDetails->setIdList($this->getIdList($authDetailsData));
        }
    }

    /**
     * @param array $authDetailsData
     *
     * @return string
     */
    protected function getIdList(array $authDetailsData)
    {
        return array_values($authDetailsData[static::ID_LIST])[0];
    }

    /**
     * @param array $authDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function hydrateAuthorizationStatus(array $authDetailsData, AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        if (!empty($authDetailsData[static::AUTHORIZATION_STATUS])) {
            $authorizationDetails->setAuthorizationStatus(
                $this->convertStatusToTransfer($authDetailsData[static::AUTHORIZATION_STATUS])
            );
        }
    }

    /**
     * @param array $authDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function hydrateCapturedAmount(array $authDetailsData, AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        if (!empty($authDetailsData[static::CAPTURED_AMOUNT])) {
            $authorizationDetails->setAuthorizationAmount($this->convertPriceToTransfer($authDetailsData[static::CAPTURED_AMOUNT]));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer $authorizationDetails
     *
     * @return void
     */
    protected function hydrateReasonCode(AmazonpayAuthorizationDetailsTransfer $authorizationDetails)
    {
        if (empty($authorizationDetails->getAuthorizationStatus()->getReasonCode())) {
            $authorizationDetails->getAuthorizationStatus()->setReasonCode('');
        }
    }
}
