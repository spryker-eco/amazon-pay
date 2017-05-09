<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayConfirmOrderReferenceResponseTransfer;

class ConfirmOrderReferenceConverter extends AbstractResponseParserConverter
{

    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'ConfirmOrderReferenceResult';
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayConfirmOrderReferenceResponseTransfer
     */
    protected function createTransferObject()
    {
        return new AmazonpayConfirmOrderReferenceResponseTransfer();
    }

}
