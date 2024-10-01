<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Model\ConfigProvider;

use Adcaptcha\Adcaptcha\Helper\Config;
use Adcaptcha\Adcaptcha\Model\ConfigProviderInterface;

class Frontend implements ConfigProviderInterface
{
    protected Config $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        return [
            'config' => [
                'enabled' => $this->config->isEnabledOnFront(),
                'placementid' => $this->config->getPlacementID(),
            ]
        ];
    }
}
