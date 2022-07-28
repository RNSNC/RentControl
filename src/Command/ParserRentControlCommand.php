<?php

namespace App\Command;

use App\Parser\FullParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use TelegramBot\Api\BotApi;

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
        $year =  date('Y');
        $month = date('m');
        $day = date('d');

//        $this->rentControl->parserRent('https://www.mosstroyprokat.ru/jsondump/'.$year.'_'.$month.'_'.$day.'_data.json');
        $this->rentControl->parserRent('https://www.mosstroyprokat.ru/jsonorder/2022_07_28_01_32_data_order.json');
//        $this->rentControl->parserRent('https://www.mosstroyprokat.ru/jsonorder/2022_07_28_02_08_data_order.json');

        $message = "Данные загрузки\n\n";

        foreach ($GLOBALS['storage'] as $storage => $val)
        {
            $data = $this->getData($storage);
            $countDeal = $data['oldDeal'] + $data['newDeal'];
            $countFiz = $data['oldFiz'] + $data['newFiz'];
            $countYr = $data['oldYr'] + $data['newYr'];
            $message .= "Склад: $storage\n".
                "Сделки - ".$countDeal." \ новые - ".$data['newDeal']." \ старые - ".$data['oldDeal']." \n".
                "Физ. - ".$countFiz." \ новые - ".$data['newFiz']." \ старые - ".$data['oldFiz']."\n".
                "Юр. - ".$countYr." \ новые - ".$data['newYr']." \ старые - ".$data['oldYr']."\n\n"
            ;
        }

        $bot = new BotApi('5466762200:AAFJ8hFBtazT1Ur-yWkO3uhdGxKycqaHgUA');
        $bot->sendMessage(716087458, $message);
//        $bot->sendMessage(528094171, $message);

        return Command::SUCCESS;
    }

    function getData($key1)
    {
        return array(
            'oldDeal' => $GLOBALS['storage'][$key1]['deal']['old'],
            'newDeal' => $GLOBALS['storage'][$key1]['deal']['new'],
            'oldFiz' => $GLOBALS['storage'][$key1]['fiz']['old'],
            'newFiz' => $GLOBALS['storage'][$key1]['fiz']['new'],
            'oldYr' => $GLOBALS['storage'][$key1]['yr']['old'],
            'newYr' => $GLOBALS['storage'][$key1]['yr']['new'],
        );
    }
}