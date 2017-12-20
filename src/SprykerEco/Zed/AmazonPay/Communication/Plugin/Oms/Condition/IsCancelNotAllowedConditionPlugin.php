<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IsCancelNotAllowedConditionPlugin extends AbstractByOrderConditionPlugin
{
    /**
     * @return array
     */
    protected function getOmsStatuses()
    {
        return [
            AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED,
            AmazonPayConfig::OMS_STATUS_CAPTURE_PENDING,
        ];
    }
}
