<?php

namespace App\Utils\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Exceptions\InvalidArgumentException;

class ApiClient
{
    /**
     * @throws InvalidArgumentException
     */
    public static function make(): AmoCRMApiClient
    {
        $apiClientFactory = new AmoCRMApiClientFactory(new OAuthConfig(), new OAuthService());
        $apiClient = $apiClientFactory->make();

        $longLivedAccessToken = new LongLivedAccessToken(env('AMO_CLIENT_LONG_TOKEN'));

        $apiClient->setAccessToken($longLivedAccessToken)
            ->setAccountBaseDomain(env('AMO_CLIENT_BASE_DOMAIN'));

        return $apiClient;

    }
}
