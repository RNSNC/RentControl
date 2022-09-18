<?php

namespace App\Model\AmoCRM;

class AmoCRM
{
    private ClientCRM $client;

    private DealCRM $deal;

    public function __construct(ClientCRM $client, DealCRM $deal)
    {
        $this->client = $client;
        $this->deal = $deal;
    }

    public function amoRentControl()
    {
        $client = $this->client->getClient();
        $this->deal->processingDealCRM($client);
    }
}