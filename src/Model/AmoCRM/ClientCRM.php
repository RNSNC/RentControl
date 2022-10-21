<?php

namespace App\Model\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;

class ClientCRM
{
    private AuthToken $authToken;

    public function __construct(AuthToken $authToken)
    {
        $this->authToken = $authToken;
    }

    public function getClient(): AmoCRMApiClient
    {
        $accessToken = $this->authToken->getAccessToken();
        $client = new AmoCRMApiClient(
            $_ENV['CRM_CLIENT_ID'],
            $_ENV['CRM_CLIENT_SECRET'],
            $_ENV['CRM_REDIRECT_URI'],
        );

        $client
            ->setAccessToken($accessToken)
            ->setAccountBaseDomain('prokatm.amocrm.ru')
        ;

        return $client;
    }
}