<?php

namespace App\Command;

use App\Parser\FullParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRentControlCommand extends Command
{
    private FullParser $rentControl;

    public function __construct(FullParser $rentControl)
    {
        parent::__construct();
        $this->rentControl = $rentControl;
    }

    protected function configure()
    {
        $this
            ->setName('parser:rent')
            ->addArgument(
                name: 'date',
                default: 0,
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument('date');
        $date = date('Y_m_d', strtotime("-$count day"));
        $link = 'https://www.mosstroyprokat.ru/jsondump/'.$date.'_data.json';
        $this->rentControl->parserRent($link);

        return Command::SUCCESS;
    }
}