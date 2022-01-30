<?php

namespace App\Order\Queries;

use App\Common\Enums\OrderStatus;
use App\Common\Models\Order;

class OrderQueries
{
    public function getFirst(array $columns = ['*']): Order
    {
        return Order::whereStatus(OrderStatus::WAIT->value)
            ->whereHas('account', fn ($q) => $q->whereNotNull('login')->whereNotNull('password'))
            ->whereRaw('not exists (select null from orders as o where o.account_id = orders.account_id'
                . ' and status = ?)', OrderStatus::PROCESSING->value)
            ->orderBy('id')
            ->firstOrFail($columns);
    }

    public function findById(int $id, $columns = ['*']): Order
    {
        return Order::whereId($id)
            ->whereStatus(OrderStatus::PROCESSING->value)
            ->with('questions:id,name')
            ->firstOrFail($columns);
    }
}
