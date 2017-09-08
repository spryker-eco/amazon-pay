<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade getFacade()
 */
class IpnController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function endpointAction()
    {
        $headersRaw = unserialize(file_get_contents('./header.txt'), []);

        $headers = [];
        foreach ($headersRaw as $headerKey =>  $headerValue) {
            if (strpos($headerKey, 'Amz')) {
                $headers[strtolower($headerKey)] =  $headerValue;
            }
        }

        $body = unserialize(file_get_contents('./body.txt'), []);

        $ipnRequestTransfer = $this->getFacade()->convertAmazonpayIpnRequest($headers, $body);
        $this->getFacade()->handleAmazonpayIpnRequest($ipnRequestTransfer);

        return new Response('Request has been processed');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function endpointDebugAction()
    {
        $headersRaw = unserialize(file_get_contents('./header.txt'), []);

        $headers = [];
        foreach ($headersRaw as $headerKey =>  $headerValue) {
            if (strpos($headerKey, 'Amz')) {
                $headers[strtolower($headerKey)] =  $headerValue;
            }
        }

        $body = unserialize(file_get_contents('./body.txt'), []);

        $ipnRequestTransfer = $this->getFacade()->convertAmazonpayIpnRequest($headers, $body);
        $this->getFacade()->handleAmazonpayIpnRequest($ipnRequestTransfer);

        return new Response('Request has been processed');
    }

}
