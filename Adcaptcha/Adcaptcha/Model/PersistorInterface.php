<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Model;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as Request;

interface PersistorInterface
{
    /**
     * Persist Data
     *
     * @param Request $request
     * @param ActionInterface $action
     * @return void
     */
    public function persist(Request $request, ActionInterface $action): void;
}
