<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface AbstractAdapterInterface
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function call(AbstractTransfer $abstractTransfer);

}
