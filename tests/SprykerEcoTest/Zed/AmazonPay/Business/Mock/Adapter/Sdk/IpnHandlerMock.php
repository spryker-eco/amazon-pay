<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

use AmazonPay\IpnHandler;

class IpnHandlerMock extends IpnHandler
{
    /**
     * @var array|null
     */
    private $headers;

    /**
     * @var string|null
     */
    private $body;

    /**
     * @var array|null
     */
    private $snsMessage;

    /**
     * @param array $headers
     * @param string $body
     * @param array|null $ipnConfig
     */
    public function __construct($headers, $body, $ipnConfig = null)
    {
        $this->headers = array_change_key_case($headers, CASE_LOWER);
        $this->body = $body;
        $this->snsMessage = json_decode($this->body, true);
    }

    /**
     * @return array
     */
    public function returnMessage()
    {
        return json_decode($this->snsMessage['Message'], true);
    }
}
