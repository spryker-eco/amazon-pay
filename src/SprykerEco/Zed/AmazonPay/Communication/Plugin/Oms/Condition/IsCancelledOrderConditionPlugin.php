<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IsCancelledOrderConditionPlugin extends AbstractByOrderConditionPlugin
{
    /**
     * @return array
     */
    protected function getStatuses()
    {
        return [
            AmazonPayConfig::OMS_STATUS_CANCELLED,
        ];
    }
}
