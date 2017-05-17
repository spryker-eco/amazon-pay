<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock;

use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayConfigMock extends AmazonpayConfig
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
        if (isset($this->additionalConfig[AmazonpayConstants::CAPTURE_NOW])) {
            return $this->additionalConfig[AmazonpayConstants::CAPTURE_NOW];
        }

        return parent::getCaptureNow();
    }

    /**
     * @return int
     */
    public function getAuthTransactionTimeout()
    {
        if (isset($this->additionalConfig[AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT])) {
            return $this->additionalConfig[AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT];
        }

        return parent::getAuthTransactionTimeout();
    }
}
