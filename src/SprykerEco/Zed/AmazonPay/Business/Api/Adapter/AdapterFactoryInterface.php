<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

interface AdapterFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createObtainProfileInformationAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createSetOrderReferenceDetailsAmazonpayAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createConfirmOrderReferenceAmazonpayAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderReferenceDetailsAmazonpayAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createAuthorizeAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createAuthorizeCaptureNowAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCloseOrderAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCancelOrderAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createRefundOrderAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderAuthorizationDetailsAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderCaptureDetailsAdapter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderRefundDetailsAdapter();

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\IpnRequestAdapter
     */
    public function createIpnRequestAdapter(array $headers, $body);

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCaptureOrderAdapter();
}
