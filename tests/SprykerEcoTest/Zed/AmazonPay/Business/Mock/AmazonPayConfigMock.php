<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConstants;

class AmazonPayConfigMock extends AmazonPayConfig
{
    /**
     * @var array
     */
    protected $additionalConfig;

    /**
     * @param array|null $additionalConfig
     */
    public function __construct($additionalConfig = null)
    {
        $this->additionalConfig = $additionalConfig;
    }

    /**
     * @return bool
     */
    public function getCaptureNow()
    {
        if (isset($this->additionalConfig[AmazonPayConstants::CAPTURE_NOW])) {
            return $this->additionalConfig[AmazonPayConstants::CAPTURE_NOW];
        }

        return parent::getCaptureNow();
    }

    /**
     * @return int
     */
    public function getAuthTransactionTimeout()
    {
        if (isset($this->additionalConfig[AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT])) {
            return $this->additionalConfig[AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT];
        }

        return parent::getAuthTransactionTimeout();
    }

    /**
     * @return bool
     */
    public function getSuccessPaymentUrl(): string
    {
        if (isset($this->additionalConfig[AmazonPayConstants::SUCCESS_PAYMENT_URL])) {
            return $this->additionalConfig[AmazonPayConstants::SUCCESS_PAYMENT_URL];
        }

        return parent::getSuccessPaymentUrl();
    }

    /**
     * @return bool
     */
    public function getFailurePaymentUrl(): string
    {
        if (isset($this->additionalConfig[AmazonPayConstants::FAILURE_PAYMENT_URL])) {
            return $this->additionalConfig[AmazonPayConstants::FAILURE_PAYMENT_URL];
        }

        return parent::getFailurePaymentUrl();
    }
}
