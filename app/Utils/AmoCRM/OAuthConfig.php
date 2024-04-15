<?php

namespace App\Utils\AmoCRM;

use AmoCRM\OAuth\OAuthConfigInterface;

class OAuthConfig implements OAuthConfigInterface
{

    /**
     * @inheritDoc
     */
    public function getIntegrationId(): string
    {
        return env('AMO_CLIENT_ID');
    }

    /**
     * @inheritDoc
     */
    public function getSecretKey(): string
    {
        return env('AMO_CLIENT_SECRET');
    }

    /**
     * @inheritDoc
     */
    public function getRedirectDomain(): string
    {
        return env('AMO_CLIENT_REDIRECT_URI');
    }
}
