<?php

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Enums\Buttons\WalletButton;
use App\Bot\Queries\WithdrawnMoneyQueries;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class WalletConversation extends Conversation
{
    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(
                KeyboardButton::create(__('buttons.wallet.top_up'))->callbackData(WalletButton::TOP_UP->value),
                KeyboardButton::create(__('buttons.wallet.withdraw'))->callbackData(WalletButton::WITHDRAW->value),
            )
            ->addRow(KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value));
    }

    public function run()
    {
        $user      = Auth::user();
        $withdraws = (new WithdrawnMoneyQueries())->getByUserId($user->id);

        $question = __('questions.wallet.balance', ['money' => $user->computed_info->money]);

        if ($withdraws->isNotEmpty()) {
            $question .= __('questions.wallet.withdraws');

            foreach ($withdraws as $id => $money) {
                $question .= __('questions.wallet.withdraw', ['num' => $id, 'money' => $money]);
            }
        }

        $this->ask(
            $question,
            fn (Answer $answer) => $this->runHandler($answer),
            ['parse_mode'       => 'markdown', ...self::getKeyboard()->toArray()]
        );
    }

    private function runHandler(Answer $answer)
    {
        $selectedValue = $answer->isInteractiveMessageReply() ? $answer->getValue() : null;

        match ($selectedValue) {
            WalletButton::TOP_UP->value   => $this->bot->startConversation(new WalletTopUpConversation()),
            WalletButton::WITHDRAW->value => $this->bot->startConversation(
                new WalletWithdrawConversation($answer->getCallbackId())
            ),
            default => $this->bot->startConversation(new MainConversation()),
        };
    }
}
