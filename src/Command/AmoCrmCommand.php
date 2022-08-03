<?php

namespace App\Command;

use App\Model\AmoCRM\ClientCRM;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AmoCrmCommand extends Command
{
    private $client;

    public function __construct(ClientCRM $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setName('amocrm')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->getClient();

        return Command::SUCCESS;
    }
}