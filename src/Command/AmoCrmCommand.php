<?php

namespace App\Command;

use App\Model\AmoCRM\AmoCRM;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AmoCrmCommand extends Command
{
    private AmoCRM $amoCRM;

    public function __construct(AmoCRM $amoCRM)
    {
        parent::__construct();
        $this->amoCRM = $amoCRM;
    }

    protected function configure()
    {
        $this
            ->setName('amocrm')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->amoCRM->amoRentControl();

        return Command::SUCCESS;
    }
}