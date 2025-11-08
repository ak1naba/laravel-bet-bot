<?php

namespace App\Telegram;

use App\Models\Sport;
use App\Models\Event;
use Telegram\Bot\Keyboard\Keyboard;

class EventsCommand extends CommandHandler
{
    public function handle($text = null)
    {
        // If user requests events list, show sports with buttons
        if ($text === '/events' || mb_strtolower($text) === '🏟 события' || mb_strtolower($text) === 'события') {
            $sports = Sport::all();

            if ($sports->isEmpty()) {
                $this->sendMessage('Пока нет доступных видов спорта.');
                return;
            }

            // Prepare a human-readable list and reply keyboard with sport buttons
            $list = "🏟 Доступные виды спорта:\n\n";
            $keyboard = Keyboard::make();
            $row = [];
            foreach ($sports as $sport) {
                $list .= "{$sport->id} — {$sport->name}\n";
                // button text will be parsed as command, use sport:{id}
                $row[] = Keyboard::button("sport:{$sport->id}");
                // push rows of 2 buttons
                if (count($row) === 2) {
                    $keyboard->row($row);
                    $row = [];
                }
            }
            if (!empty($row)) {
                $keyboard->row($row);
            }

            $list .= "\nНажмите кнопку ниже, чтобы увидеть события выбранного вида спорта.";

            $this->sendMessage($list, $keyboard);
            return;
        }

        // If text starts with sport:, parse id and show events
        if (str_starts_with($text, 'sport:')) {
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
