<?php

namespace App\Model\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ClientCRM
{
    private AuthToken $authToken;

    private ParameterBagInterface $parameter;

    public function __construct(
        AuthToken $authToken,
        ParameterBagInterface $parameter,
    )
    {
        $this->authToken = $authToken;
        $this->parameter = $parameter;
    }

    public function getClient(): AmoCRMApiClient
    {
        $accessToken = $this->authToken->getAccessToken();
        $client = new AmoCRMApiClient(
            $this->parameter->get('crmClientId'),
            $this->parameter->get('crmClientSecret'),
            $this->parameter->get('crmRedirectUri'),
        );

        $client
            ->setAccessToken($accessToken)
            ->setAccountBaseDomain('prokatm.amocrm.ru')
        ;

        return $client;
    }
}