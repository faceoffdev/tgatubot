<?php

namespace App\Bot\Queries;

use App\Common\Models\MonobankReferralUrl;

class MonobankReferralUrlQueries
{
    public function getLastUrl(int $excludeUserId): string
    {
        return (string) MonobankReferralUrl::where('user_id', '!=', $excludeUserId)
            ->orderBy('id', 'desc')
            ->value('url');
    }
}
