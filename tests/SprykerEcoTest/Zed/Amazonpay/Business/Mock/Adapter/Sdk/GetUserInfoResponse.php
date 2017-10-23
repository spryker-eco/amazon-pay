<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

class GetUserInfoResponse
{
    /**
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
    }

    /**
     * @return array
     */
    public function convertToResponseParser()
    {
        return [
            'user_id' => 'amzn1.account.AERNK6U4PBPH36GCKOQOZLVCJPOA',
            'name' => 'John Doe',
            'postal_code' => '08915',
            'email' => 'john@doe.xxx',
        ];
    }
}
