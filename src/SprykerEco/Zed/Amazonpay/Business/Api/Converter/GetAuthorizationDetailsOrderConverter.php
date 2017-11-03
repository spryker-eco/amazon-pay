<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

class GetAuthorizationDetailsOrderConverter extends AbstractAuthorizeOrderConverter
{
    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'GetAuthorizationDetailsResult';
    }
}
