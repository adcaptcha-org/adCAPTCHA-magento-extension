<?php
/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

declare(strict_types=1);

namespace Adcaptcha\Adcaptcha\Model;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Adcaptcha\Adcaptcha\Helper\Config;

class Validator
{
    protected Curl $curl;

    protected Json $json;

    protected Config $config;

    protected array $errors;

    /**
     * @param Curl $curl
     * @param Json $json
     * @param Config $config
     */
    public function __construct(
        Curl $curl,
        Json $json,
        Config $config
    ) {
        $this->curl = $curl;
        $this->json = $json;
        $this->config = $config;
    }

    /**
     * Validate the success token
     *
     * @param string|null $token
     * @return bool
     */
    public function isTokenValid(?string $token): bool
    {
        if (!$token) {
            $this->errors = ['general'];
            return false;
        }
        if (!$this->getApiKey()) {
            $this->errors = ['missing-apikey'];
            return false;
        }

        $url = $this->getVerifyTokenUrl();
        $body = json_encode([
            'token' => $token,
        ]);

        $curl = $this->curl;
        $curl->setTimeout(3);
        $curl->setHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getApiKey(),
        ]);
        $curl->post($url, $body);
        $responseBody = $this->json->unserialize($curl->getBody());

        $result = $responseBody;

        $message = $result['message'] ?? null;

        if ($message && $message === 'Token verified') {
            return true;
        }

        $this->errors = ['general'];
        return false;
    }

    public function isConfigurationValid(string $apiKey, string $placementID): bool
    {

        if (!$apiKey || !$placementID) {
            $this->errors = ['config'];
            return false;
        }

        $url = 'https://api.adcaptcha.com/v1/placements/' . $placementID;

        $curl = $this->curl;
        $curl->setTimeout(3);
        $curl->setHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ]);
        $curl->get($url);
        $responseBody = $this->json->unserialize($curl->getBody());

        if (is_string($responseBody)) {
            $response = json_decode($responseBody, true);
        } else {
            $response = $responseBody;
        }

        if ($response && isset($response['id']) && $response['id'] === $placementID) {
            return true;
        }

        $this->errors = ['config'];
        return false;
    }

    /**
     * Retrieve error message
     *
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        $messages = [];

        foreach ($this->getErrorCodes() as $code) {
            $messages[] = __($this->getErrorMessage($code));
        }

        return $messages;
    }

    /**
     * Retrieve all error codes
     *
     * @return string[]
     */
    public function getErrorCodes(): array
    {
        return $this->errors;
    }

    /**
     * Retrieve error message from error code
     *
     * @param string $code
     * @return string
     */
    protected function getErrorMessage(string $code): string
    {
        $messages = [
            'general'     => 'Incomplete captcha, Please try again.',
            'missing-apikey' => 'Api Key missing, please contact the owner of this site.',
            'config' => 'Invalid API key or Placement ID, Please try again.',
        ];

        return $messages[$code] ?? 'Unknown Error. Please contact owner of the site.';
    }

    protected function getApiKey(): ?string
    {
        return $this->config->getApiKey();
    }

    protected function getPlacementID(): string
    {
        return $this->config->getPlacementID();
    }

    protected function getVerifyInputSettingsUrl(): string
    {
        return $this->config->getVerifyInputSettingsUrl();
    }

    protected function getVerifyTokenUrl(): string
    {
        return $this->config->getVerifyTokenUrl();
    }
}
