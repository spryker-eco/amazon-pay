<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Amazonpay;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class AmazonpayConfig extends AbstractBundleConfig implements AmazonpayConfigInterface
{

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->get(AmazonpayConstants::CLIENT_ID);
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->get(AmazonpayConstants::ACCESS_KEY_ID);
    }

    /**
     * @return string
     */
    public function getSellerId()
    {
        return $this->get(AmazonpayConstants::SELLER_ID);
    }

    /**
     * @return string
     */
    public function getSecretAccessKey()
    {
        return $this->get(AmazonpayConstants::SECRET_ACCESS_KEY);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->get(AmazonpayConstants::CLIENT_SECRET);
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->get(AmazonpayConstants::REGION);
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @return string
     */
    public function isSandbox()
    {
        return (bool)$this->get(AmazonpayConstants::SANDBOX);
    }

    /**
     * @return string
     */
    public function getErrorReportLevel()
    {
        return $this->get(AmazonpayConstants::ERROR_REPORT_LEVEL);
    }

    /**
     * @return bool
     */
    public function getCaptureNow()
    {
        return (bool)$this->get(AmazonpayConstants::CAPTURE_NOW);
    }

    /**
     * @return int
     */
    public function getAuthTransactionTimeout()
    {
        return $this->get(AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT);
    }

    /**
     * @return string
     */
    public function getWidgetScriptPath()
    {
        return $this->get(AmazonpayConstants::WIDGET_SCRIPT_PATH);
    }

    /**
     * @return string
     */
    public function getWidgetScriptPathSandbox()
    {
        return $this->get(AmazonpayConstants::WIDGET_SCRIPT_PATH_SANDBOX);
    }

    /**
     * @return bool
     */
    public function getPopupLogin()
    {
        return (bool)$this->get(AmazonpayConstants::WIDGET_POPUP_LOGIN);
    }

    /**
     * @return string
     */
    public function getButtonSize()
    {
        return $this->get(AmazonpayConstants::WIDGET_BUTTON_SIZE);
    }

    /**
     * @return string
     */
    public function getButtonColor()
    {
        return $this->get(AmazonpayConstants::WIDGET_BUTTON_COLOR);
    }

    /**
     * @return string
     */
    public function getButtonType()
    {
        return $this->get(AmazonpayConstants::WIDGET_BUTTON_TYPE);
    }

}
