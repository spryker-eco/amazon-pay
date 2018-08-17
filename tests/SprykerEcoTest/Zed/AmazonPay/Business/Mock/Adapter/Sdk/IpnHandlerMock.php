<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

use PayWithAmazon\IpnHandler;

class IpnHandlerMock extends IpnHandler
{
    /**
     * @var array|null
     */
    private $headers = null;

    /**
     * @var null
     */
    private $body = null;

    /**
     * @var mixed|null
     */
    private $snsMessage = null;

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
     * @return mixed
     */
    public function returnMessage()
    {
        return json_decode($this->snsMessage['Message'], true);
    }
}
