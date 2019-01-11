<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Controller;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\AmazonPay\Communication\AmazonPayCommunicationFactory getFactory()
 */
class IpnController extends AbstractController
{
    use LoggerTrait;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function endpointAction(Request $request)
    {
        $headers = $this->convertHeaders($request->headers->all());
        $body = file_get_contents('php://input');

        $ipnRequestTransfer = $this->getFacade()->convertAmazonPayIpnRequest($headers, $body);
        $this->getFacade()->handleAmazonPayIpnRequest($ipnRequestTransfer);

        return new Response('Request has been processed');
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    protected function convertHeaders(array $headers)
    {
        $assocHeaders = [];

        foreach ($headers as $key => $headerValues) {
            $assocHeaders[$key] = $headerValues[0];
        }

        return $assocHeaders;
    }
}
