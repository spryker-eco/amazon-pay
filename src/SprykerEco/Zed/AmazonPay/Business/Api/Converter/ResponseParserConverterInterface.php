<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use AmazonPay\ResponseInterface;

interface ResponseParserConverterInterface
{
    /**
     * @param \AmazonPay\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function convert(ResponseInterface $responseParser);
}
