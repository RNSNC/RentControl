<?php

namespace App\Model\AmoCRM;

use App\Entity\TokenCRM;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthToken
{
    private $doctrine;

    private $parameter;

    public function __construct(
        ManagerRegistry $doctrine,
        ParameterBagInterface $parameter,
    )
    {
        $this->doctrine = $doctrine;
        $this->parameter = $parameter;
    }

    public function getAccessToken(): ?AccessToken
    {
        $token = $this->getDataToken();

        if (!$token || $token->getDateRefreshToken() < new \DateTime() ) {
            $this->setNewToken($this->parameter->get('crmCode'), "authorization_code");
            $token = $this->getDataToken();
        }

        if ($token->getDateAccessToken() < new \DateTime()){
            $this->setNewToken($token->getRefreshToken(), 'refresh_token');
        }

        return new AccessToken([
            'access_token' => $token->getAccessToken(),
            'expires_in' => $token->getExpiresIn(),
        ]);
    }

    private function setNewToken($code, $grantType): void
    {
        $client = new Client();

        $response = $client->request(
            'POST',
            'https://prokatm.amocrm.ru/oauth2/access_token',
            [
                'form_params'  =>  [
                    "client_id" => $this->parameter->get('crmClientId'),
                    "client_secret" => $this->parameter->get('crmClientSecret'),
                    "grant_type" => $grantType,
                    "code" => $code,
                    "redirect_uri" => $this->parameter->get('crmRedirectUri'),
                ]
            ]
        );

        $jsonResponse = json_decode($response->getBody());

        $token = $this->doctrine->getRepository(TokenCRM::class)->find(1);
        if (!$token) $token = new TokenCRM();
        $token
            ->setTokenType($jsonResponse->token_type)
            ->setExpiresIn($jsonResponse->expires_in)
            ->setAccessToken($jsonResponse->access_token)
            ->setRefreshToken($jsonResponse->refresh_token)
            ->setDateAccessToken((new \DateTime())->modify('+'.$jsonResponse->expires_in.' second'))
            ->setDateRefreshToken((new \DateTime())->modify('+3 month'))
        ;
        $this->doctrine->getManager()->persist($token);
        $this->doctrine->getManager()->flush();
    }

    private function getDataToken()
    {
        return $this->doctrine->getRepository(TokenCRM::class)->find(1);
    }
}