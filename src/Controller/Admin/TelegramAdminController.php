<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\TelegramCounterparty;

class TelegramAdminController extends AbstractController
{
    #[Route('/admin/telegram_ajax', name: 'telegram_ajax')]
    public function telegramAjax(Request $request, TelegramCounterparty $telegramCounterparty): Response
    {
        $date1 = $request->query->get('date1');
        $date2 = $request->query->get('date2');
        if (!$date1 || !$date2) return new Response('{"message":"Выберите дату!","color":"red"}');
        $id = $_ENV['TELEGRAM_1'];
        $telegramCounterparty->sendInChat($date1, date('Y-m-d',strtotime($date2.'+1 day')), $id);
        return new Response('{"message":"Сообщение доставлено","color":"green"}');
    }
}