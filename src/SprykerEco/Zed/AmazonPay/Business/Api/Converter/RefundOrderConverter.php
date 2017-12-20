<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

class RefundOrderConverter extends AbstractRefundOrderConverter
{
    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'RefundResult';
    }
}
