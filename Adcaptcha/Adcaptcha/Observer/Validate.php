<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Observer;

use Exception;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\Response\Http as Response;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Adcaptcha\Adcaptcha\Helper\Config;
use Adcaptcha\Adcaptcha\Model\PersistorInterface;
use Adcaptcha\Adcaptcha\Model\Validator;

abstract class Validate implements ObserverInterface
{
    protected ManagerInterface $messageManager;

    protected Response $response;

    protected Validator $validator;

    protected Json $json;

    protected Config $config;

    protected ?PersistorInterface $persistor = null;

    protected array $actions = [];

    public ?ActionInterface $action = null;

    public ?Request $request = null;

    /**
     * @param ManagerInterface $messageManager
     * @param Response $response
     * @param Validator $validator
     * @param Json $json
     * @param Config $config
     * @param PersistorInterface|null $persistor
     * @param array $data
     */
    public function __construct(
        ManagerInterface $messageManager,
        Response $response,
        Validator $validator,
        Json $json,
        Config $config,
        ?PersistorInterface $persistor = null,
        array $data = []
    ) {
        $this->messageManager = $messageManager;
        $this->response       = $response;
        $this->validator      = $validator;
        $this->json           = $json;
        $this->config         = $config;
        $this->persistor      = $persistor;
        $this->actions        = $data['actions'] ?? [];
    }

    /**
     * Validate adcaptcha token
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $this->action = $observer->getEvent()->getData('controller_action');
        $this->request = $observer->getEvent()->getData('request');

        if ($this->canValidate()) {
            if ($this->getSuccessToken()) {
                if (!$this->validator->isTokenValid($this->getSuccessToken())) {
                    $this->persistor?->persist($this->request, $this->action);
                    $errorMessages = implode(', ', $this->validator->getErrorMessages());
                    $this->error(__('%1', implode(', ', $this->validator->getErrorMessages())));
                }
                return;
            }

            if ($this->isSavingAdcapConfig()) {
                if (!$this->validator->isConfigurationValid($this->getApiKey(), $this->getPlacementID())) {
                    $this->persistor?->persist($this->request, $this->action);
                    $this->error(__('%1', implode(', ', $this->validator->getErrorMessages())));
                }
                return;
            }
        }
    }

    public function getSuccessToken(): ?string
    {
        return $this->request?->getParam('adcaptcha_successToken') ?? null;
    }

    public function getApiKey(): ?string
    {
        return $this->request?->getParam('groups')['settings']['fields']['apikey']['value'] ?? null;
    }

    public function getPlacementID(): string
    {
        return $this->request?->getParam('groups')['settings']['fields']['placementid']['value'] ?? null;
    }

    public function isSavingAdcapConfig(): bool {
        if (isset($this->request?->getParam('config_state')['adcaptcha_settings']) && $this->request?->getParam('config_state')['adcaptcha_settings'] === '1') {
            return true;
        }

        return false;
    }

    /**
     * Send error
     *
     * @param Phrase $message
     * @return void
     */
    protected function error(Phrase $message): void
    {
        $this->messageManager->addErrorMessage($message);
        $this->response->setRedirect($this->request?->getServer('HTTP_REFERER', '/') ?? '/');

        $this->response->sendResponse();
        exit();
    }

    /**
     * Can validate action
     *
     * @return bool
     */
    public function canValidate(): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        if (!$this->request?->isPost()) {
            return false;
        }

        return true;
    }

    /**
     * Retrieve if validator is globally enabled
     *
     * @return bool
     */
    abstract public function isEnabled(): bool;
}
