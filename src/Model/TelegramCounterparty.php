<?php

namespace App\Model;

use App\Entity\Document;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Persistence\ManagerRegistry;

class TelegramCounterparty
{
    private $parameter;

    private $doctrine;

    public function __construct(
        ParameterBagInterface $parameterBag,
        ManagerRegistry $doctrine,
    )
    {
        $this->parameter = $parameterBag;
        $this->doctrine = $doctrine;
    }

    public function sendInChat($parameter, $date)
    {
        $bot = new BotApi($this->parameter->get('telegramToken'));
        $id = $this->parameter->get($parameter);

        $documents = $this->doctrine->getRepository(Document::class)->findAllGreaterDate(['dateCreate'=> $date]);

        $info = array();

        foreach ($documents as $document)
        {
            $storage = $document->getStorage()->getName();
            if (!isset($info[$storage]))
            {
                $info[$storage] = array(
                    'countDeal' => 0, 'newDeal' => 0, 'oldDeal' => 0,
                    'countFiz' => 0, 'newFiz' => 0, 'oldFiz' => 0,
                    'countYr' => 0, 'newYr' => 0, 'oldYr' => 0,
                );
            }

            $type = ($document->getCounterparty()->getTypePerson() == 'Физ. лицо')?'Fiz':'Yr';

            $info[$storage] = $this->setInfo($info[$storage], $type, $document->isCounterpartyNew());
        }

        $message = "Данные загрузки\n\n";

        $allCountDeal = 0;
        $allNewDeal = 0;
        $allOldDeal = 0;

        foreach ($info as $storage => $val)
        {
            $message .= "Склад: $storage\n".
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

        $bot->sendMessage($id, $message, null, false, null, $keyboard);
    }

    private function setInfo($storage, $type, $status)
    {
        $status = ($status) ? 'new' : 'old';
        $storage['countDeal']++;
        $storage[$status.'Deal']++;
        $storage['count'.$type]++;
        $storage[$status.$type]++;
        return $storage;
    }
}