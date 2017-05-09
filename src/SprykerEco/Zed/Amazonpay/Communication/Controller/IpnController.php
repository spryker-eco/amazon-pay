<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Amazonpay\Business\AmazonpayFacade getFacade()
 */
class IpnController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function endpointAction()
    {
        $headers = getallheaders();
        $body = file_get_contents('php://input');

        $ipnRequestTransfer = $this->getFacade()->convertAmazonpayIpnRequest($headers, $body);
        $this->getFacade()->handleAmazonpayIpnRequest($ipnRequestTransfer);

        return new Response('Request has been processed');
    }

}
