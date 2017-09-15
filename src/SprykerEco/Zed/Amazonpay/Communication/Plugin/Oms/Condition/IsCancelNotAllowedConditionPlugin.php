<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IsCancelNotAllowedConditionPlugin extends AbstractByOrderConditionPlugin
{

    /**
     * @return array
     */
    protected function getStatuses()
    {
        return [
            AmazonpayConstants::OMS_STATUS_CANCELLED,
            AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED,
            AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING,
        ];
    }

    /**
     * @return bool
     */
    protected function statusFoundCondition()
    {
        return true;
    }

}
