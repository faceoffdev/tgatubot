<?php

namespace App\Bot\Queries;

use App\Common\Models\WithdrawnMoney;
use Illuminate\Support\Collection;

class WithdrawnMoneyQueries
{
    public function getByUserId(int $id): Collection
    {
        return WithdrawnMoney::whereUserId($id)
            ->whereDispatched(false)
            ->toBase()
            ->pluck('money', 'id');
    }
}
