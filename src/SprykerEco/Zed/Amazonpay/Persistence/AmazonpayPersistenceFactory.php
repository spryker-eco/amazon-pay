<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Persistence;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Shared\Amazonpay\AmazonpayConfig getConfig()
 * @method \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer getQueryContainer()
 */
class AmazonpayPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function createPaymentAmazonpayQuery()
    {
        return SpyPaymentAmazonpayQuery::create();
    }

}
