<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IsAuthExpiredConditionPlugin extends AbstractByOrderItemConditionPlugin
{
    /**
     * @return string
     */
    protected function getPaymentStatus()
    {
        return AmazonPayConfig::STATUS_EXPIRED;
    }
}
