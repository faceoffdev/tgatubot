<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 19.01.2021
 * Time: 21:15
 */

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\MainButton;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class MainConversation extends Conversation
{
    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(
                KeyboardButton::create(__('buttons.main.wallet'))->callbackData(MainButton::WALLET->value),
                KeyboardButton::create(__('buttons.main.market'))->callbackData(MainButton::MARKET->value),
            )
            ->addRow(KeyboardButton::create(__('buttons.main.referrals'))->callbackData(MainButton::REFERRALS->value))
            ->addRow(KeyboardButton::create(__('buttons.main.settings'))->callbackData(MainButton::SETTINGS->value));
    }

    public function run()
    {
        $this->ask(
            __('questions.main.ask'),
            fn (Answer $answer) => $this->runHandler($answer),
            self::getKeyboard()->toArray()
        );
    }

    public function runHandler(Answer $answer)
    {
        $selectedValue = $answer->isInteractiveMessageReply() ? $answer->getValue() : null;

        match ($selectedValue) {
            MainButton::WALLET->value    => $this->bot->startConversation(new WalletConversation()),
            MainButton::MARKET->value    => $this->bot->startConversation(new QuestionConversation()),
            MainButton::REFERRALS->value => $this->bot->startConversation(new ReferralsConversation()),
            MainButton::SETTINGS->value  => $this->bot->startConversation(new SettingsConversation()),
            default                      => $this->bot->listen(),
        };
    }
}
