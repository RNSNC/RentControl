<?php

namespace App\Command;

use AmoCRM\Client\AmoCRMApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AmoCrmCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('amocrm')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $apiClient = new AmoCRMApiClient(
            '7d26328e-d661-43be-a558-8d70c3bd815d',
            'tZJNNGjaxh0d5td7k2DMhWSdLKddA8fvCYCctcibRud738FadcBPkfYPUXYYt5eV',
            'https://prokatm.amocrm.ru/oauth2/access_token',
        );

        $leads = $apiClient->leads()->get();

        var_dump($leads);

        return Command::SUCCESS;
    }
}