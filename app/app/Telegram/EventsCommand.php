<?php

namespace App\Telegram;

use App\Models\Sport;
use App\Models\Event;

class EventsCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $normalized = is_string($text) ? mb_strtolower(trim($text)) : $text;

        // show sports list with inline buttons
        if ($text === '/events' || $normalized === '🏟 события' || $normalized === 'события') {
            $sports = Sport::all();

            if ($sports->isEmpty()) {
                $this->sendMessage('Пока нет доступных видов спорта.');
                return;
            }

            $list = "🏟 Доступные виды спорта:\n\n";
            $inlineKeyboard = [];
            $row = [];

            foreach ($sports as $sport) {
                $list .= "{$sport->id} — {$sport->name}\n";
                $row[] = ['text' => $sport->name, 'callback_data' => "sport:{$sport->id}"];
                // 2 buttons per row
                if (count($row) === 2) {
                    $inlineKeyboard[] = $row;
                    $row = [];
                }
            }
            if (!empty($row)) {
                $inlineKeyboard[] = $row;
            }

            $list .= "\nНажмите кнопку ниже, чтобы увидеть события выбранного вида спорта.";

            // send using telegram API directly to include inline keyboard as JSON
            $this->telegram->sendMessage([
                'chat_id' => $this->chatId,
                'text' => $list,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => $inlineKeyboard])
            ]);

            return;
        }

        // If text starts with sport:, parse id and show events (also used by callback_data)
        if (is_string($text) && str_starts_with($text, 'sport:')) {
            $parts = explode(':', $text);
            $sportId = isset($parts[1]) ? intval($parts[1]) : null;

            if (!$sportId) {
                $this->sendMessage('Неверный идентификатор вида спорта.');
                return;
            }

            $sport = Sport::find($sportId);
            if (!$sport) {
                $this->sendMessage('Вид спорта не найден.');
                return;
            }

            $events = Event::where('sport_id', $sport->id)
                ->whereIn('status', ['scheduled', 'live'])
                ->orderBy('start_time')
                ->get();

            if ($events->isEmpty()) {
                $this->sendMessage("По виду спорта '{$sport->name}' нет событий в статусе scheduled/live.");
                return;
            }

            $message = "🏅 События для вида спорта: <b>{$sport->name}</b>\n\n";
            $inlineKeyboard = [];
            foreach ($events as $ev) {
                $start = $ev->start_time ? $ev->start_time : '—';
                $message .= "• <b>{$ev->title}</b> — {$start}\n";
                $inlineKeyboard[] = [
                    ['text' => 'Подробнее', 'callback_data' => "event:DETAILS:{$ev->id}"]
                ];
            }
            $message .= "\nНажмите 'Подробнее' для информации о событии.";
            $this->telegram->sendMessage([
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => $inlineKeyboard])
            ]);
            return;
        }

        // Если text начинается с event:DETAILS:, показать детали события, участников и кнопки маркетов
        if (is_string($text) && str_starts_with($text, 'event:DETAILS:')) {
            $parts = explode(':', $text);
            $eventId = isset($parts[2]) ? intval($parts[2]) : null;
            if (!$eventId) {
                $this->sendMessage('Неверный идентификатор события.');
                return;
            }
            $event = \App\Models\Event::find($eventId);
            if (!$event) {
                $this->sendMessage('Событие не найдено.');
                return;
            }
            $participants = $event->participants;
            $markets = $event->markets;

            // Получаем TelegramUser и определяем таймзону
            $telegramUser = null;
            if ($this->userData && isset($this->userData['id'])) {
                $telegramUser = \App\Models\TelegramUser::find($this->userData['id']);
            }
            $timezone = 'Europe/Moscow'; // default
            if ($telegramUser && !empty($telegramUser->languagecode)) {
                if ($telegramUser->languagecode === 'en') $timezone = 'Europe/London';
                if ($telegramUser->languagecode === 'ru') $timezone = 'Europe/Moscow';
            }
            $start = $event->start_time ? $event->start_time->setTimezone($timezone)->format('d.m.Y H:i') : '—';
            $end = $event->end_time ? $event->end_time->setTimezone($timezone)->format('d.m.Y H:i') : '—';

            $msg = "🏟 <b>{$event->title}</b>\n";
            $msg .= "🕒 <b>Время:</b> {$start} - {$end} ({$timezone})\n";
            $msg .= "📄 <b>Описание:</b> {$event->description}\n";
            $msg .= "\n👥 <b>Участники:</b>\n";
            foreach ($participants as $p) {
                $msg .= "• {$p->duplicate_team}\n";
            }
            $msg .= "\n💼 <b>Маркет:</b>\n";
            $inlineKeyboard = [];
            $row = [];
            foreach ($markets as $market) {
                $row[] = ['text' => $market->description, 'callback_data' => "market:{$market->id}"];
                if (count($row) === 2) {
                    $inlineKeyboard[] = $row;
                    $row = [];
                }
            }
            if (!empty($row)) {
                $inlineKeyboard[] = $row;
            }
            $this->telegram->sendMessage([
                'chat_id' => $this->chatId,
                'text' => $msg,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => $inlineKeyboard])
            ]);
            return;
        }

        // Fallback
        $this->sendMessage('Команда событий: отправьте /events или выберите кнопку События в меню.');
    }
}
