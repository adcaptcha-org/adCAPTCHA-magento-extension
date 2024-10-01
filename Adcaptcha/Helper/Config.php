<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    public const ADCAPTCHA_CONFIG_PATH_APIKEY = 'adcaptcha/settings/apikey';
    public const ADCAPTCHA_CONFIG_PATH_PLACEMENTID = 'adcaptcha/settings/placementid';

    public const ADCAPTCHA_CONFIG_PATH_FRONTEND_ENABLED = 'adcaptcha/frontend/enabled';

    public const ADCAPTCHA_CONFIG_PATH_ADMINHTML_ENABLED = 'adcaptcha/adminhtml/enabled';

    public function isEnabledOnFront(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::ADCAPTCHA_CONFIG_PATH_FRONTEND_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is adCAPTCHA enabled on admin
     *
     * @return bool
     */
    public function isEnabledOnAdmin(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::ADCAPTCHA_CONFIG_PATH_ADMINHTML_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve API Key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::ADCAPTCHA_CONFIG_PATH_APIKEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Placement ID
     *
     * @return string
     */
    public function getPlacementID(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::ADCAPTCHA_CONFIG_PATH_PLACEMENTID,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getVerifyInputSettingsUrl(): string
    {
        return 'https://api.adcaptcha.com/v1/placements/';
    }

    public function getVerifyTokenUrl(): string
    {
        return 'https://api.adcaptcha.com/v1/verify';
    }

    /**
     * Retrieve default action
     *
     * @return string
     */
    public function getAction(): string
    {
        return 'default';
    }
}
