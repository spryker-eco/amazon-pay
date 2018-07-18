<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

class AuthorizeOrderConverter extends AbstractAuthorizeOrderConverter
{
    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'AuthorizeResult';
    }
}
