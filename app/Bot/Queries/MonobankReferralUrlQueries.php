<?php

namespace App\Bot\Queries;

use App\Common\Models\MonobankReferralUrl;

class MonobankReferralUrlQueries
{
    public function getLastUrl(array $columns = ['*']): MonobankReferralUrl
    {
        return MonobankReferralUrl::orderBy('id', 'desc')
            ->first($columns);
    }
}
