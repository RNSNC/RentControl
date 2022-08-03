<?php

namespace App\Command;

use App\Parser\FullParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRentControlCommand extends Command
{
    /**
     * @var FullParser
     */
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
        $link = 'https://www.mosstroyprokat.ru/jsondump/'.date('Y').'_'.date('m').'_'.date('d').'_data.json';
        $this->rentControl->parserRent($link);

        return Command::SUCCESS;
    }
}