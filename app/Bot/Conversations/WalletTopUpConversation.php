<?php

namespace App\Bot\Conversations;

use App\Bot\Actions\PayMonobankUrlAction;
use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Enums\Buttons\WalletButton;
use App\Bot\Queries\MonobankReferralUrlQueries;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class WalletTopUpConversation extends Conversation
{
    public const PATTERN_MONOBANK_REFERRAL_URL = "/^https:\/\/monobank\.ua\/r\/\w{1,10}$/i";

    public static function getKeyboard(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value));
    }

    public static function getKeyboardWithUrl(): Keyboard
    {
        return (new Keyboard())
            ->addRow(
                KeyboardButton::create(
                    __('buttons.wallet.pay_url', ['price' => config('app.price_pay_ref_url')])
                )
                    ->callbackData(WalletButton::PAY_URL->value)
            )
            ->addRow(KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value));
    }

    public function run()
    {
        $userId         = Auth::id();
        $configMonobank = config('services.monobank');
        $monobankUrl    = (new MonobankReferralUrlQueries())->getLastUrl($userId);

        $q = __('questions.wallet.top_up', [
            'send_url' => $configMonobank['send_url'],
            'id'       => $userId,
            'card'     => $configMonobank['card'],
        ]);

        if ($monobankUrl) {
            $q .= __('questions.wallet.sponsor', [
                'url'   => $monobankUrl,
                'money' => $configMonobank['cashback_count'],
            ]);
        }

        $keyboard = $monobankUrl
            ? self::getKeyboard()
            : self::getKeyboardWithUrl();

        $this->ask(
            $q,
            fn (Answer $a) => $this->runHandler($a),
            ['parse_mode'  => 'markdown', ...$keyboard->toArray()]
        );
    }

    private function runHandler(Answer $answer)
    {
        $selectedValue = $answer->isInteractiveMessageReply() ? $answer->getValue() : null;

        match ($selectedValue) {
            WalletButton::PAY_URL->value => $this->askPayUrl($answer->getCallbackId()),
            default                      => $this->bot->startConversation(new MainConversation()),
        };
    }

    public function askPayUrl(?string $callbackId = null)
    {
        $user  = Auth::user();
        $price = config('app.price_pay_ref_url');

        if ($user->computedInfo->money < $price) {
            $text = __('errors.wallet.not_enough_money', ['price' => $price, 'money' => $user->computedInfo->money]);

            if ($callbackId) {
                $this->bot->sendRequest('answerCallbackQuery', [
                    'callback_query_id' => $callbackId,
                    'show_alert'        => true,
                    'text'              => $text,
                ]);
            } else {
                $this->bot->reply($text);
            }

            $this->run();

            return;
        }

        $this->ask(
            __('questions.wallet.ask_url'),
            fn (Answer $answer) => $this->payUrlHandler($answer),
            self::getKeyboard()->toArray()
        );
    }

    private function payUrlHandler(Answer $answer)
    {
        if ($answer->isInteractiveMessageReply()) {
            $this->run();

            return;
        }

        $url = $answer->getText();

        if (!preg_match(self::PATTERN_MONOBANK_REFERRAL_URL, $url)) {
            $this->bot->reply(__('errors.validation.url'));
            $this->askPayUrl();

            return;
        }

        if ((new PayMonobankUrlAction())->execute(Auth::id(), $url)) {
            $this->bot->reply(__('success.wallet.pay_url'));
        } else {
            $this->bot->reply(__('errors.general'));
        }

        $this->bot->startConversation(new MainConversation());
    }
}
