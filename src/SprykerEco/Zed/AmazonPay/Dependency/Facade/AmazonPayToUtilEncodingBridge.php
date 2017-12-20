<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Facade;

class AmazonPayToUtilEncodingBridge implements AmazonPayToUtilEncodingInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct($utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param mixed $jsonValue
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson($jsonValue, $options = null, $depth = null)
    {
        return $this->utilEncodingService->encodeJson($jsonValue, $options, $depth);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }
}
