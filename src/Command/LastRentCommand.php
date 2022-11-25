<?php

namespace App\Command;

use App\Entity\Counterparty;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LastRentCommand extends Command
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this->setName('last:rent');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $counterparties = $this->doctrine->getManager()->getRepository(Counterparty::class)->findAll();
        foreach ($counterparties as $counterparty)
        {
            $counterparty->setDateLastRent(
                $counterparty->getDocuments()->first()->getDateCreate()
            );
            $summa = 0;
            foreach ($counterparty->getDocuments() as $document)
            {
                $summa += $document->getSummaArenda();
            }
            $counterparty->setSumRents($summa);
        }
        $this->doctrine->getManager()->flush();

        return Command::SUCCESS;
    }
}