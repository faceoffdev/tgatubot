<?php

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Enums\Buttons\QuestionTypeButton;
use App\Bot\Queries\QuestionQueries;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class QuestionConversation extends Conversation
{
    public function __construct(
        protected int $semester = 1,
        protected string $group = '',
        protected string $discipline = ''
    ) {
    }

    public function run()
    {
        if ($this->discipline && $this->group && $this->semester) {
            $this->askType();

            return;
        }

        $semestersByCourse = (new QuestionQueries())->getSemestersByCourse();

        $keyboard = new Keyboard();

        foreach ($semestersByCourse as $semesters) {
            $keyboard->addRow(...$semesters->all());
        }

        $keyboard->addRow(
            KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value),
            KeyboardButton::create(__('buttons.common.add_question'))->url(config('botman.telegram.support_url'))
        );

        $this->ask(
            __('questions.questions.ask_course'),
            fn (Answer $answer) => $this->runHandler($answer),
            $keyboard->toArray()
        );
    }

    private function runHandler(Answer $answer)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : null;

        if (is_numeric($value)) {
            $this->semester = $value;

            $this->askGroup();
        } else {
            $this->bot->startConversation(new MainConversation());
        }
    }

    public function askGroup()
    {
        $groupsByChunk = (new QuestionQueries())->getGroups($this->semester);

        $keyboard = new Keyboard();

        foreach ($groupsByChunk as $groups) {
            $keyboard->addRow(...$groups->all());
        }

        $keyboard->addRow(
            KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value),
            KeyboardButton::create(__('buttons.common.add_question'))->url(config('botman.telegram.support_url'))
        );

        $this->ask(
            __('questions.questions.ask_group'),
            fn (Answer $answer) => $this->groupHandler($answer),
            $keyboard->toArray()
        );
    }

    private function groupHandler(Answer $answer)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : CommonButton::BACK->value;

        if ($value === CommonButton::BACK->value) {
            $this->group = '';

            $this->run();
        } else {
            $this->group = $value;

            $this->askDiscipline();
        }
    }

    public function askDiscipline()
    {
        $disciplinesByChunk = (new QuestionQueries())->getDisciplines($this->semester, $this->group);

        $keyboard = new Keyboard();

        foreach ($disciplinesByChunk as $disciplines) {
            $keyboard->addRow(...$disciplines->all());
        }

        $keyboard->addRow(
            KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value),
            KeyboardButton::create(__('buttons.common.add_question'))->url(config('botman.telegram.support_url'))
        );

        $this->ask(
            __('questions.questions.ask_discipline'),
            fn (Answer $answer) => $this->disciplineHandler($answer),
            $keyboard->toArray()
        );
    }

    private function disciplineHandler(Answer $answer)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : CommonButton::BACK->value;

        if ($value === CommonButton::BACK->value) {
            $this->discipline = '';

            $this->askGroup();
        } else {
            $this->discipline = $value;

            $this->askType();
        }
    }

    public function askType()
    {
        $keyboard = new Keyboard();

        $keyboard
            ->addRow(
                KeyboardButton::create(__('buttons.questions.type.mass'))->callbackData(QuestionTypeButton::MASS->value),
                KeyboardButton::create(__('buttons.questions.type.single'))->callbackData(QuestionTypeButton::SINGLE->value),
            )
            ->addRow(
                KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value),
            );

        $this->ask(
            __('questions.questions.ask_type'),
            fn (Answer $answer) => $this->typeHandler($answer),
            $keyboard->toArray()
        );
    }

    private function typeHandler(Answer $answer)
    {
        $value = $answer->isInteractiveMessageReply() ? $answer->getValue() : CommonButton::BACK->value;

        match ($value) {
            QuestionTypeButton::MASS->value => $this->bot->startConversation(
                new QuestionMassConversation($this->semester, $this->group, $this->discipline)
            ),
            QuestionTypeButton::SINGLE->value => $this->bot->startConversation(
                new QuestionSingleConversation($this->semester, $this->group, $this->discipline)
            ),
            default => $this->askDiscipline()
        };
    }
}
