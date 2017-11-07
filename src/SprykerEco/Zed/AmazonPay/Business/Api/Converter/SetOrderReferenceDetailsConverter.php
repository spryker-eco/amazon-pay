<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayResponseTransfer;

class SetOrderReferenceDetailsConverter extends AbstractResponseParserConverter
{
    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'SetOrderReferenceDetailsResult';
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, array $response)
    {
        $responseTransfer->setShippingAddress($this->extractShippingAddress($response));

        return parent::setBody($responseTransfer, $response);
    }
}
