<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Block\Adcaptcha;

use Adcaptcha\Adcaptcha\Model\ConfigProviderInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Config extends Template
{
    protected SerializerInterface $serializer;

    protected ConfigProviderInterface $configProvider;

    /**
     * @param Context $context
     * @param SerializerInterface $serializer
     * @param ConfigProviderInterface $configProvider
     * @param mixed[] $data
     */
    public function __construct(
        Context $context,
        SerializerInterface $serializer,
        ConfigProviderInterface $configProvider,
        array $data = []
    ) {
        $this->serializer     = $serializer;
        $this->configProvider = $configProvider;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve configuration
     *
     * @return string[]
     */
    public function getAdcaptchaConfig(): array
    {
        return $this->configProvider->getConfig();
    }

    /**
     * Retrieve serialized config
     *
     * @return string
     */
    public function getSerializedAdcaptchaConfig(): string
    {
        return (string)$this->serializer->serialize($this->getAdcaptchaConfig());
    }
}
