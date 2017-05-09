<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication;

interface AmazonpayCommunicationFactoryInterface
{

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToSalesInterface
     */
    public function getSalesFacade();

}
