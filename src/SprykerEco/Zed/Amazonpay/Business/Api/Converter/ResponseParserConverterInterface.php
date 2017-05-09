<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use PayWithAmazon\ResponseInterface;

interface ResponseParserConverterInterface
{

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AbstractTransfer
     */
    public function convert(ResponseInterface $responseParser);

}
