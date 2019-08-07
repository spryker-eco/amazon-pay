<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\AmazonPay;

interface AmazonPayConfigInterface
{
    /**
     * @return string
     */
    public function getClientId();

    /**
     * @return string
     */
    public function getAccessKeyId();

    /**
     * @return string
     */
    public function getSellerId();

    /**
     * @return string
     */
    public function getSecretAccessKey();

    /**
     * @return string
     */
    public function getClientSecret();

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @return string
     */
    public function getCurrencyIsoCode();

    /**
     * @return bool
     */
    public function isSandbox();

    /**
     * @return string
     */
    public function getErrorReportLevel();

    /**
     * @return bool
     */
    public function getCaptureNow();

    /**
     * @return int
     */
    public function getAuthTransactionTimeout();

    /**
     * @return string
     */
    public function getWidgetScriptPath();

    /**
     * @return string
     */
    public function getWidgetScriptPathSandbox();

    /**
     * @return bool
     */
    public function getEnableIsolateLevelRead();
}
