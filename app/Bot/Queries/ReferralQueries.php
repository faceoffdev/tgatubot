<?php

namespace App\Bot\Queries;

use App\Common\Models\Referral;

class ReferralQueries
{
    public function getCount(int $id): int
    {
        return Referral::whereReferrerId($id)->count();
    }
}
