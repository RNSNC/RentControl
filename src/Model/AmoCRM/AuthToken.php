<?php

namespace App\Model\AmoCRM;

use App\Entity\TokenCRM;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use DateTime;

class AuthToken
{
    private ManagerRegistry $doctrine;

    private ParameterBagInterface $parameter;

    public function __construct(
        ManagerRegistry $doctrine,
        ParameterBagInterface $parameter,
    )
    {
        $this->doctrine = $doctrine;
        $this->parameter = $parameter;
    }

    public function getAccessToken(): AccessToken
    {
        $token = $this->getDataToken();

        if (!$token || $token->getDateRefreshToken() < new DateTime() ) {
            $this->setNewToken("authorization_code", $this->parameter->get('crmCode'));
            $token = $this->getDataToken();
        }

        if ($token->getDateAccessToken() < new DateTime()){
            $this->setNewToken("refresh_token", $token->getRefreshToken());
            $token = $this->getDataToken();
        }

        return new AccessToken([
            'access_token' => $token->getAccessToken(),
            'expires_in' => $token->getExpiresIn(),
        ]);
    }

    private function setNewToken($grantType, $code): void
    {
        $client = new Client();

        $parameter = ($grantType == "refresh_token") ? "refresh_token" : "code";

        $response = $client->request(
            'POST',
            'https://prokatm.amocrm.ru/oauth2/access_token',
            array('form_params' => [
                "client_id" => $this->parameter->get('crmClientId'),
                "client_secret" => $this->parameter->get('crmClientSecret'),
                "grant_type" => $grantType,
                $parameter => $code,
                "redirect_uri" => $this->parameter->get('crmRedirectUri'),
            ])
        );

        $jsonResponse = json_decode($response->getBody());

        $token = $this->doctrine->getRepository(TokenCRM::class)->find(1);
        if (!$token) $token = new TokenCRM();
        $token
            ->setTokenType($jsonResponse->token_type)
            ->setExpiresIn($jsonResponse->expires_in)
            ->setAccessToken($jsonResponse->access_token)
            ->setRefreshToken($jsonResponse->refresh_token)
            ->setDateAccessToken((new DateTime())->modify('+'.$jsonResponse->expires_in.' second'))
            ->setDateRefreshToken((new DateTime())->modify('+3 month'))
        ;
        $this->doctrine->getManager()->persist($token);
        $this->doctrine->getManager()->flush();
    }

    private function getDataToken()
    {
        return $this->doctrine->getRepository(TokenCRM::class)->find(1);
    }
}