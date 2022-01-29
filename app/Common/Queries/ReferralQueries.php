<?php

namespace App\Common\Queries;

use App\Common\Models\Referral;

class ReferralQueries
{
    public function getReferrerId(int $referralId): ?int
    {
        return Referral::whereReferralId($referralId)->value('referrer_id');
    }
}
