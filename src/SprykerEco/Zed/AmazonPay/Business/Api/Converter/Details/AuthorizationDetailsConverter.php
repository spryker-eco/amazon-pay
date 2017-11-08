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
    const AUTHORIZATION_BILLING_ADDRESS = 'AuthorizationBillingAddress';
    const SOFT_DESCRIPTOR = 'SoftDescriptor';

    protected $detailsMapToTransferFields = [
        self::CAPTURE_NOW => AmazonpayAuthorizationDetailsTransfer::CAPTURE_NOW,
        self::CAPTURED_AMOUNT => AmazonpayAuthorizationDetailsTransfer::CAPTURED_AMOUNT,
        self::SELLER_AUTHORIZATION_NOTE => AmazonpayAuthorizationDetailsTransfer::SELLER_AUTHORIZATION_NOTE,
        self::AUTHORIZATION_FEE => AmazonpayAuthorizationDetailsTransfer::AUTHORIZATION_FEE,
        self::AUTHORIZATION_AMOUNT => AmazonpayAuthorizationDetailsTransfer::AUTHORIZATION_AMOUNT,
        self::SOFT_DECLINE => AmazonpayAuthorizationDetailsTransfer::SOFT_DECLINE,
        self::CREATION_TIMESTAMP => AmazonpayAuthorizationDetailsTransfer::CREATION_TIMESTAMP,
        self::EXPIRATION_TIMESTAMP => AmazonpayAuthorizationDetailsTransfer::EXPIRATION_TIMESTAMP,
        self::AMAZON_AUTHORIZATION_ID => AmazonpayAuthorizationDetailsTransfer::AMAZON_AUTHORIZATION_ID,
        self::AUTHORIZATION_REFERENCE_ID => AmazonpayAuthorizationDetailsTransfer::AUTHORIZATION_REFERENCE_ID,
        self::CAPTURE_NOW => AmazonpayAuthorizationDetailsTransfer::CAPTURE_NOW,
        self::AUTHORIZATION_STATUS => AmazonpayAuthorizationDetailsTransfer::AUTHORIZATION_STATUS,
        self::AUTHORIZATION_BILLING_ADDRESS => AmazonpayAuthorizationDetailsTransfer::AUTHORIZATION_BILLING_ADDRESS,
        self::SOFT_DESCRIPTOR => AmazonpayAuthorizationDetailsTransfer::SOFT_DESCRIPTOR,
    ];

    /**
     * @param array $authDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer
     */
    public function convert(array $authDetailsData)
    {
        $authorizationDetails = new AmazonpayAuthorizationDetailsTransfer();

        $mappedData = $this->mapDetailsToTransferFields($authDetailsData);
        $authorizationDetails->fromArray($mappedData, true);

        $this->hydrateIdList($authDetailsData, $authorizationDetails);
        $this->hydrateAuthorizationStatus($authDetailsData, $authorizationDetails);
        $this->hydrateCapturedAmount($authDetailsData, $authorizationDetails);
        $this->hydrateReasonCode($authorizationDetails);

        return $authorizationDetails;
    }

    /**
     * @param array $authDetailsData
     *
     * @return array
     */
    protected function mapDetailsToTransferFields(array $authDetailsData)
    {
        $result = [];
        foreach ($this->detailsMapToTransferFields as $detailField => $transferField) {
            $result[$transferField] = $authDetailsData[$detailField] ?? null;
        }

        return $result;
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
            $statusTransfer = $this->convertStatusToTransfer($authDetailsData[static::AUTHORIZATION_STATUS]);
            $authorizationDetails->setAuthorizationStatus($statusTransfer);
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
