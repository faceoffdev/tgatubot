<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 19.01.2021
 * Time: 21:15
 */

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Queries\QuestionSingleQueries;
use App\Common\Helper\StrHelper;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class QuestionSingleConversation extends Conversation
{
    protected QuestionSingleQueries $questionQueries;

    protected int $currentPage = 1;

    /** @var array<int> */
    protected array $selectedIds = [];

    public function __construct(protected int $semester, protected string $group, protected string $discipline)
    {
        $this->questionQueries = new QuestionSingleQueries($semester, $group, $discipline);
    }

    public function run()
    {
        $keyboard = new Keyboard();
        $paginator = $this->questionQueries->paginate($this->currentPage, ['id', 'name', 'price']);
        $lastPage = $paginator->lastPage();
        $buttons = [];

        foreach ($paginator->items() as $item) {
            $name = array_key_exists($item->id, $this->selectedIds) ? "✅ {$item->name}" : $item->name;
            $name = StrHelper::strimwidth($name, 0, 33);

            $keyboard->addRow(KeyboardButton::create("{$name} - {$item->price}₴")->callbackData($item->id));
        }

        if ($this->currentPage !== 1 && $lastPage > 1) {
            $buttons[] = KeyboardButton::create('«')->callbackData('prev');
        }

        if ($this->currentPage !== $lastPage && $lastPage > 1) {
            $buttons[] = KeyboardButton::create('»')->callbackData('next');
        }

        if ($buttons) {
            $keyboard->addRow(...$buttons);
        }

        $keyboard
            ->addRow(
                KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value),
                KeyboardButton::create('💵 К оплате')->callbackData('pay'),
            );

        $this->ask(
            __('questions.questions.single.ask_question'),
            fn (Answer $answer) => $this->runHandler($answer),
            $keyboard->toArray()
        );
    }

    private function runHandler(Answer $answer)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : CommonButton::BACK->value;

        if (is_numeric($value)) {
            if (array_key_exists($value, $this->selectedIds)) {
                unset($this->selectedIds[$value]);
            } else {
                $this->selectedIds[$value] = true;
            }
        } elseif ($value === 'prev') {
            $this->currentPage--;
        } elseif ($value === 'next') {
            $this->currentPage++;
        } elseif ($value === 'pay') {
            $this->bot->startConversation(new OrderConversation(array_keys($this->selectedIds)));

            return;
        } else {
            $this->bot->startConversation(new QuestionConversation($this->semester, $this->group, $this->discipline));

            return;
        }

        $this->run();
    }
}
