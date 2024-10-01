<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Model;

interface ConfigProviderInterface
{
    public function getConfig(): array;
}
