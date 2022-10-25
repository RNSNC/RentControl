<?php

namespace App\Command;

use App\Model\TelegramCounterparty;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TelegramMessageCommand extends Command
{
    private TelegramCounterparty $telegramCounterparty;

    public function __construct(TelegramCounterparty $telegramCounterparty)
    {
        parent::__construct();
        $this->telegramCounterparty = $telegramCounterparty;
    }

    protected function configure()
    {
        $this
            ->setName('telegram')
            ->addArgument(
                name: 'date',
                default: 0,
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument('date');
        $date = date('Y-m-d', strtotime("-$count day"));
        $this->telegramCounterparty->sendInChats($date,'TELEGRAM_1', 'TELEGRAM_2');

        return Command::SUCCESS;
    }
}