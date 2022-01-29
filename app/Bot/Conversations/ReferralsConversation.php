<?php

namespace App\Bot\Conversations;

use App\Bot\Enums\Buttons\CommonButton;
use App\Bot\Queries\ReferralQueries;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Facades\Auth;

class ReferralsConversation extends Conversation
{
    public static function getKeyboardMenu(): Keyboard
    {
        return (new Keyboard())
            ->addRow(KeyboardButton::create(__('buttons.common.back'))->callbackData(CommonButton::BACK->value));
    }

    public function run()
    {
        $user = Auth::user();

        $this->ask(
            __('questions.referrals', [
                'id'      => $user->id,
                'percent' => config('app.percent_from_referrals'),
                'count'   => (new ReferralQueries())->getCount($user->id),
                'money'   => $user->computed_info->money_from_referrals,
            ]),
            fn ()         => $this->bot->startConversation(new MainConversation()),
            ['parse_mode' => 'markdown', ...self::getKeyboardMenu()->toArray()]
        );
    }
}
