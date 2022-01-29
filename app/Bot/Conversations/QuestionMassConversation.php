<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 19.01.2021
 * Time: 21:15
 */

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Enums\Buttons\QuestionMassButton;
use App\Bot\Queries\QuestionMassQueries;
use App\Common\Helper\IntHelper;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class QuestionMassConversation extends Conversation
{
    protected QuestionMassQueries $questionQueries;

    public function __construct(protected int $semester, protected string $group, protected string $discipline)
    {
        $this->questionQueries = new QuestionMassQueries($semester, $group, $discipline);
    }

    public function run()
    {
        $keyboard = (new Keyboard())
            ->addRow(
                KeyboardButton::create(__('buttons.questions.action.all'))
                    ->callbackData(QuestionMassButton::ALL->value)
            );

        foreach ($this->questionQueries->getModules() as $module) {
            $keyboard
                ->addRow(
                    KeyboardButton::create(__('buttons.questions.action.module', ['num' => $module]))
                        ->callbackData(QuestionMassButton::MODULE->value . $module),
                    KeyboardButton::create(__('buttons.questions.action.module_not_lab', ['num' => $module]))
                        ->callbackData(QuestionMassButton::MODULE_NOT_LAB->value . $module),
                    KeyboardButton::create(__('buttons.questions.action.module_with_lab', ['num' => $module]))
                        ->callbackData(QuestionMassButton::MODULE_WITH_LAB->value . $module)
                );
        }

        $keyboard
            ->addRow(
                KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value),
            );

        $this->ask(
            __('questions.questions.mass.ask_action'),
            fn (Answer $answer) => $this->runHandler($answer),
            $keyboard->toArray()
        );
    }

    private function runHandler(Answer $answer)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : CommonButton::BACK->value;

        if ($value === QuestionMassButton::ALL->value) {
            $ids = $this->questionQueries->getAllIds();
        } elseif (stripos($value, QuestionMassButton::MODULE_WITH_LAB->value) !== false) {
            $ids = $this->questionQueries->getIdsByModuleWithLaboratories(IntHelper::parse($value));
        } elseif (stripos($value, QuestionMassButton::MODULE_NOT_LAB->value) !== false) {
            $ids = $this->questionQueries->getIdsByModuleNotLaboratories(IntHelper::parse($value));
        } elseif (stripos($value, QuestionMassButton::MODULE->value) !== false) {
            $ids = $this->questionQueries->getIdsByModule(IntHelper::parse($value));
        } else {
            $this->bot->startConversation(new QuestionConversation($this->semester, $this->group, $this->discipline));

            return;
        }

        $this->bot->startConversation(new OrderConversation($ids));
    }
}
