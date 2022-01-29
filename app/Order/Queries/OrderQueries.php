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
            ->orderBy('id')
            ->with('questions', fn ($q) => $q->select(['id', 'delay'])->orderBy('sort'))
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
