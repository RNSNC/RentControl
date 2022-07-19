<?php

namespace App\Command;

use App\Parser\FullParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRentControlCommand extends Command
{
    private $rentControl;

    public function __construct(FullParser $rentControl)
    {
        parent::__construct();
        $this->rentControl = $rentControl;
    }

    protected function configure()
    {
        $this
            ->setName('parser:rent')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->rentControl->parserRent('https://www.mosstroyprokat.ru/jsonorder/2021_09_17_01_50_data_order.json');
        return Command::SUCCESS;
    }
}