<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

interface ArrayConverterInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function convert(array $data);
}
