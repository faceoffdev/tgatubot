<?php

namespace App\Order\Actions;

use App\Common\Models\UserComputedInfo;
use App\Common\Queries\ReferralQueries;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Throwable;

final class AddMoneyForReferralAction
{
    private ReferralQueries $referralQueries;

    public function __construct(private LoggerInterface $logger)
    {
        $this->referralQueries = new ReferralQueries();
    }

    public function execute(int $userId, float $price): ?int
    {
        $referrerId = $this->referralQueries->getReferrerId($userId);

        if (!$referrerId) {
            return null;
        }

        $money = (config('app.percent_from_referrals') / 100) * $price;

        DB::beginTransaction();

        try {
            UserComputedInfo::whereId($referrerId)->increment('money', $money);
            UserComputedInfo::whereId($referrerId)->increment('money_from_referrals', $money);

            DB::commit();

            return $referrerId;
        } catch (Throwable $e) {
            DB::rollBack();

            $this->logger->error($e->getMessage(), [
                'user_id'     => $userId,
                'referrer_id' => $referrerId,
            ]);
        }

        return null;
    }
}
