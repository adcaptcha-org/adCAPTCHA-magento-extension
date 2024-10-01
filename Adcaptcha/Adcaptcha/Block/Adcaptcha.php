<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Block;

use Adcaptcha\Adcaptcha\Helper\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Filter\FilterManager;

class Adcaptcha extends Template
{
    protected $_template = 'Adcaptcha_Magento::adcaptcha.phtml';

    protected FilterManager $filter;

    protected Config $config;

    /**
     * @param Context $context
     * @param FilterManager $filter
     * @param Config $config
     * @param mixed[] $data
     */
    public function __construct(
        Context $context,
        FilterManager $filter,
        Config $config,
        array $data = []
    ) {
        $this->filter = $filter;
        $this->config = $config;

        parent::__construct($context, $data);
    }

    public function getAction(): string
    {
        return $this->getData('action') ?: $this->config->getAction();
    }

    public function getId(): string
    {
        return 'adcaptcha-' . $this->filter->translitUrl($this->getAction());
    }

    public function getPlacementID(): string
    {
        return $this->config->getPlacementID();
    }
}
