<?php

namespace App\Model;

use App\Entity\Document;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Doctrine\Persistence\ManagerRegistry;

class TelegramCounterparty
{
    private ManagerRegistry $doctrine;

    private BotApi $bot;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->bot = new BotApi($_ENV['TELEGRAM_TOKEN']);
    }

    public function sendInChat($date1, $date2, $id)
    {
        $message = $this->prepareMessage($date1, $date2);
        $this->bot->sendMessage($id, $message['message'], replyMarkup: $message['keyboard']);
    }

    public function sendInChats($date, ...$parameters)
    {
        $ids = array();
        foreach ($parameters as $parameter)
        {
            $ids[] = $_ENV[$parameter];
        }

        $message = $this->prepareMessage($date, date('Y-m-d', strtotime('+1 day')));

        foreach ($ids as $id)
        {
            $this->bot->sendMessage($id, $message['message'], replyMarkup: $message['keyboard']);
        }
    }

    public function prepareMessage($date1, $date2): array
    {
        $documents = $this->doctrine->getRepository(Document::class)->findIntervalDate($date1, $date2);
        $info = array();

        foreach ($documents as $document)
        {
            $subdivision = $document->getSubdivision()->getName();
            if (!isset($info[$subdivision]))
            {
                $info[$subdivision] = array(
                    'countDeal' => 0, 'newDeal' => 0, 'oldDeal' => 0,
                    'countFiz' => 0, 'newFiz' => 0, 'oldFiz' => 0,
                    'countYr' => 0, 'newYr' => 0, 'oldYr' => 0,
                );
            }

            $type = ($document->getCounterparty()->getTypePerson() == 'Физ. лицо')?'Fiz':'Yr';

            $info[$subdivision] = $this->setInfo($info[$subdivision], $type, $document->isCounterpartyNew());
        }

        $message = "Данные загрузки\n\n";

        $allCountDeal = 0;
        $allNewDeal = 0;
        $allOldDeal = 0;

        foreach ($info as $subdivision => $val)
        {
            $message .= "Склад: $subdivision\n".
                "Сделки - ".$val['countDeal']." \ новые - ".$val['newDeal']." \ старые - ".$val['oldDeal']." \n".
                "Физ. - ".$val['countFiz']." \ новые - ".$val['newFiz']." \ старые - ".$val['oldFiz']."\n".
                "Юр. - ".$val['countYr']." \ новые - ".$val['newYr']." \ старые - ".$val['oldYr']."\n\n"
            ;
            $allCountDeal += $val['countDeal'];
            $allNewDeal += $val['newDeal'];
            $allOldDeal += $val['oldDeal'];
        }

        $message .= "Всего - $allCountDeal \ новые - $allNewDeal \ старые - $allOldDeal \n";

        $keyboard = new InlineKeyboardMarkup(array(array(
            ['text' => 'Документы', 'url' => 'http://185.135.80.209/admin/app/document/list'],
            ['text' => 'Контрагенты', 'url' => 'http://185.135.80.209/admin/app/counterparty/list'],
        )));

        return [
            'message' => $message,
            'keyboard' => $keyboard,
        ];
    }

    private function setInfo($subdivision, $type, $status)
    {
        $status = ($status) ? 'new' : 'old';
        $subdivision['countDeal']++;
        $subdivision[$status.'Deal']++;
        $subdivision['count'.$type]++;
        $subdivision[$status.$type]++;
        return $subdivision;
    }
}