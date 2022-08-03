<?php

namespace App\Command;

use App\Model\TelegramCounterparty;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TelegramMessageCommand extends Command
{
    /**
     * @var TelegramCounterparty
     */
    private $telegramCounterparty;

    public function __construct(TelegramCounterparty $telegramCounterparty)
    {
        parent::__construct();
        $this->telegramCounterparty = $telegramCounterparty;
    }

    protected function configure()
    {
        $this
            ->setName('telegram')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = date('Y-m-d');
        $this->telegramCounterparty->sendInChat('telegramEvgenij', $date);
        $this->telegramCounterparty->sendInChat('telegramMaksim', $date);

        return Command::SUCCESS;
    }
}