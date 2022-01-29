<?php

namespace App\Bot\Queries;

use App\Common\Models\Account;

class AccountQueries
{
    public function getFirstByUserId(int $userId, $columns = ['*']): Account
    {
        return Account::whereUserId($userId)->firstOrFail($columns);
    }

    public function notExistsLoginOrPassword(int $userId): bool
    {
        return Account::whereUserId($userId)
            ->where(fn ($q) => $q->whereNull('login')->orWhereNull('password'))
            ->exists();
    }
}
