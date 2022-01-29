<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 19.01.2021
 * Time: 21:15
 */

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Enums\Buttons\SettingButton;
use App\Bot\Queries\AccountQueries;
use App\Common\Models\Account;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class SettingsConversation extends Conversation
{
    public static function getKeyboard(Account $account)
    {
        $actionLogin    = $account->login ? 'update' : 'set';
        $actionPassword = $account->password ? 'update' : 'set';

        return (new Keyboard())
            ->addRow(
                KeyboardButton::create(__("buttons.settings.{$actionLogin}.login"))
                    ->callbackData(SettingButton::LOGIN->value),
                KeyboardButton::create(__("buttons.settings.{$actionPassword}.password"))
                    ->callbackData(SettingButton::PASSWORD->value),
            )
            ->addRow(KeyboardButton::create(__('buttons.common.support'))->url(config('botman.telegram.support_url')))
            ->addRow(KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK));
    }

    public function run()
    {
        $account = (new AccountQueries())->getFirstByUserId(Auth::id());

        $question = sprintf(
            'Данные аккаунта' . PHP_EOL . PHP_EOL . 'Логин: %s' . PHP_EOL . 'Пароль: %s',
            $account->login ?? '-',
            $account->password ? '***' : '-'
        );

        $this->ask(
            $question,
            fn (Answer $answer) => $this->runHandler($answer),
            self::getKeyboard($account)->toArray()
        );
    }

    private function runHandler(Answer $answer)
    {
        $selectedValue = $answer->isInteractiveMessageReply() ? $answer->getValue() : null;

        match ($selectedValue) {
            SettingButton::LOGIN->value    => $this->askLogin(),
            SettingButton::PASSWORD->value => $this->askPassword(),
            default                        => $this->bot->startConversation(new MainConversation()),
        };
    }

    public function askLogin()
    {
        $this->ask(__('questions.settings.ask_login'), fn (Answer $answer) => $this->loginHandler($answer));
    }

    public function askPassword()
    {
        $this->ask(__('questions.settings.ask_password'), fn (Answer $answer) => $this->passwordHandler($answer));
    }

    private function loginHandler(Answer $answer)
    {
        Account::whereUserId(Auth::id())->update(['login' => $answer->getText()]);

        $this->run();
    }

    private function passwordHandler(Answer $answer)
    {
        Account::whereUserId(Auth::id())->update(['password' => $answer->getText()]);

        $this->run();
    }
}
