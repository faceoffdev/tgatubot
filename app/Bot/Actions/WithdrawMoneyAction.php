<?php

namespace App\Bot\Actions;

use App\Common\Models\UserComputedInfo;
use App\Common\Models\WithdrawnMoney;
use Illuminate\Support\Facades\DB;
use Throwable;

final class WithdrawMoneyAction
{
    public function execute(int $userId, string $card, float $money, float $commission): ?int
    {
        DB::beginTransaction();

        try {
            UserComputedInfo::whereId($userId)->decrement('money', $money);

            $money -= $commission;

            $id = WithdrawnMoney::insertGetId([
                'user_id' => $userId,
                'money'   => $money,
                'card'    => $card,
            ]);

            DB::commit();
        } catch (Throwable) {
            DB::rollBack();
        }

        return $id ?? null;
    }
}
