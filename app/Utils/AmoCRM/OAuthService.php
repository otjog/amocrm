<?php

namespace App\Utils\AmoCRM;

use AmoCRM\OAuth\OAuthServiceInterface;
use Illuminate\Support\Facades\Storage;
use League\OAuth2\Client\Token\AccessTokenInterface;

class OAuthService implements OAuthServiceInterface
{

    /**
     * @inheritDoc
     */
    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {

        $data = [
            'accessToken' => $accessToken->getToken(),
            'expires' => $accessToken->getExpires(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'baseDomain' => $baseDomain,
        ];

        Storage::put('token_info.json', json_encode($data));
    }
}
