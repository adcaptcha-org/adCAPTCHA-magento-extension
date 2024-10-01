<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Observer\Validate;

use Adcaptcha\Adcaptcha\Observer\Validate;

class Admin extends Validate
{
    /**
     * Retrieve if validator is globally enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabledOnAdmin();
    }
}
