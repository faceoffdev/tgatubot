<?php

namespace App\Bot\Actions;

use App\Bot\Decorators\ChatTelegramUser;
use App\Common\Models\Account;
use App\Common\Models\Referral;
use App\Common\Models\User;
use App\Common\Models\UserComputedInfo;

final class RegistrationAction
{
    public function execute(ChatTelegramUser $chatUser, ?int $referralId): void
    {
        $chatId = $chatUser->getChatId();

        $userExists = User::whereId($chatId)->exists();

        if ($userExists) {
            User::whereId($chatId)->update([
                'name'     => $chatUser->getFullName(),
                'username' => $chatUser->getUsername(),
            ]);
        } else {
            User::insert([
                'id'       => $chatId,
                'name'     => $chatUser->getFullName(),
                'username' => $chatUser->getUsername(),
            ]);

            UserComputedInfo::insert([
                'id' => $chatId,
            ]);

            Account::insert([
                'user_id' => $chatId,
            ]);
        }

        if ($referralId && !$userExists && !Referral::whereReferralId($chatId)->exists()) {
            Referral::insert([
                'referral_id' => $chatId,
                'referrer_id' => $referralId,
            ]);
        }
    }
}
