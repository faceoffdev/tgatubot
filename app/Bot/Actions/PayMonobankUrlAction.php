<?php

namespace App\Bot\Actions;

use App\Common\Models\MonobankReferralUrl;
use App\Common\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class PayMonobankUrlAction
{
    public function execute(int $userId, string $url): bool
    {
        DB::beginTransaction();

        try {
            User::whereId($userId)->decrement('money', 5);
            MonobankReferralUrl::insert([
                'user_id' => $userId,
                'url'     => $url,
            ]);

            DB::commit();

            return true;
        } catch (Throwable) {
            DB::rollBack();

            return false;
        }
    }
}
