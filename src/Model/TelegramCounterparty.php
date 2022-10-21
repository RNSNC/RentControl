<?php

namespace App\Model;

use App\Entity\Document;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Doctrine\Persistence\ManagerRegistry;

class TelegramCounterparty
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function sendInChat($date, ...$parameters)
    {
        $bot = new BotApi($_ENV['TELEGRAM_TOKEN']);
        $ids = array();
        foreach ($parameters as $parameter)
        {
            $ids[] = $_ENV[$parameter];
        }

        $documents = $this->doctrine->getRepository(Document::class)->findAllGreaterDate(['dateCreate'=> $date]);

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

        foreach ($ids as $id)
        {
            $bot->sendMessage($id, $message, null, false, null, $keyboard);
        }
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