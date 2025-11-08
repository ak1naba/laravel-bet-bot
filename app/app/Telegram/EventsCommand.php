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
                ->orderBy('start_time')
                ->get();

            if ($events->isEmpty()) {
                $this->sendMessage("По виду спорта '{$sport->name}' событий не найдено.");
                return;
            }

            $message = "🏅 События для вида спорта: <b>{$sport->name}</b>\n\n";
            foreach ($events as $ev) {
                $start = $ev->start_time ? $ev->start_time : '—';
                $message .= "• <b>{$ev->title}</b> — {$start}\n";
            }

            $this->sendMessage($message);
            return;
        }

        // Fallback
        $this->sendMessage('Команда событий: отправьте /events или выберите кнопку События в меню.');
    }
}
