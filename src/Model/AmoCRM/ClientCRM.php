<?php

namespace App\Model\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ClientCRM
{
    /**
     * @var AuthToken
     */
    private $authToken;

    private $parameter;

    public function __construct(
        AuthToken $authToken,
        ParameterBagInterface $parameter,
    )
    {
        $this->authToken = $authToken;
        $this->parameter = $parameter;
    }

    public function getClient()
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

        $model = $client->leads()->getOne(27485736);
        $model->setName('test222');
        $model->setStatusId(49417662);
        $client->leads()->updateOne($model);
    }
}