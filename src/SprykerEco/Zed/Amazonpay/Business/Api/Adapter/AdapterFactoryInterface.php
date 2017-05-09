<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

interface AdapterFactoryInterface
{

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createObtainProfileInformationAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createSetOrderReferenceDetailsAmazonpayAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createConfirmOrderReferenceAmazonpayAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createGetOrderReferenceDetailsAmazonpayAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createAuthorizeQuoteAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createAuthorizeOrderAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createAuthorizeCaptureNowOrderAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createCloseOrderAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createCancelOrderAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createRefundOrderAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createGetOrderAuthorizationDetailsAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createGetOrderCaptureDetailsAdapter();

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createGetOrderRefundDetailsAdapter();

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\IpnRequestAdapter
     */
    public function createIpnRequestAdapter(array $headers, $body);

}
