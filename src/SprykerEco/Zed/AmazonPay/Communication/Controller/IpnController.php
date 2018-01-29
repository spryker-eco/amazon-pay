<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Controller;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacadeInterface getFacade()
 */
class IpnController extends AbstractController
{
    use LoggerTrait;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function endpointAction()
    {
        /** @var string[] $headers */
        $headers = getallheaders();
        $body = file_get_contents('php://input');

        $this->getLogger()->info('IPN request',
            [
                'headers' => $headers,
                'body' => $body,
            ]
        );

        $ipnRequestTransfer = $this->getFacade()->convertAmazonPayIpnRequest($headers, $body);
        $this->getFacade()->handleAmazonPayIpnRequest($ipnRequestTransfer);

        return new Response('Request has been processed');
    }
}
