<?php

namespace App\Bot\Conversations;

use App\Bot\Actions\WithdrawMoneyAction;
use App\Bot\Enums\Buttons\CommonButton;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class WalletWithdrawConversation extends Conversation
{
    protected float $withdrawMoney = 0;

    public function __construct(protected string $callbackId = '')
    {
    }

    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value));
    }

    public static function getSupportKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.support'))
                ->url(config('botman.telegram.support_url')));
    }

    public function getCommission(): float
    {
        return round(($this->withdrawMoney * 0.01) + 5, 2);
    }

    public function run()
    {
        $user             = Auth::user();
        $minWithdrawMoney = config('app.min_withdraw_money');

        if ($user->computed_info->money <= ($minWithdrawMoney * 0.01 + 7)) {
            $this->bot->sendRequest('answerCallbackQuery', [
                'callback_query_id' => $this->callbackId,
                'show_alert'        => true,
                'text'              => __('errors.wallet.not_enough_money_withdraw', [
                    'money'     => $user->computed_info->money,
                    'min_money' => $minWithdrawMoney,
                ]),
            ]);

            $this->bot->startConversation(new WalletConversation());

            return;
        }

        $this->ask(
            __('questions.wallet.ask_money'),
            fn (Answer $answer) => $this->runHandler($answer),
            self::getKeyboard()->toArray()
        );
    }

    private function runHandler(Answer $answer)
    {
        if ($answer->isInteractiveMessageReply()) {
            $this->bot->startConversation(new WalletConversation());

            return;
        }

        $minWithdrawMoney    = config('app.min_withdraw_money');
        $this->withdrawMoney = floatval($answer->getText());

        if ($this->withdrawMoney < config('app.min_withdraw_money')) {
            $this->bot->reply(__('errors.validation.min_money', ['money' => $minWithdrawMoney]));

            $this->run();

            return;
        }

        if (Auth::user()->money <= $this->withdrawMoney + $this->getCommission()) {
            $this->bot->reply(__('errors.validation.max_money'));

            $this->run();
        } else {
            $this->askCard();
        }
    }

    private function askCard()
    {
        return $this->ask(
            __('questions.wallet.ask_card'),
            fn (Answer $answer) => $this->cardHandler($answer),
            self::getKeyboard()->toArray()
        );
    }

    private function cardHandler(Answer $answer)
    {
        if (!$answer->isInteractiveMessageReply()) {
            $card       = $answer->getText();
            $commission = $this->getCommission();

            if ($id = (new WithdrawMoneyAction())->execute(Auth::id(), $card, $this->withdrawMoney, $commission)) {
                $this->bot->reply(
                    __('success.wallet.withdraw', [
                        'num'        => $id,
                        'money'      => $this->withdrawMoney - $commission,
                        'commission' => $commission,
                    ]),
                    ['parse_mode' => 'markdown', ...self::getSupportKeyboard()->toArray()]
                );
            } else {
                $this->bot->reply(__('errors.general'));
            }
        }

        $this->bot->startConversation(new MainConversation());
    }
}
