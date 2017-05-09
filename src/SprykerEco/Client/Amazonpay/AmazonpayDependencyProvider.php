<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Amazonpay;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class AmazonpayDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_ZED_REQUEST = 'zed request';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        return $container;
    }

}
