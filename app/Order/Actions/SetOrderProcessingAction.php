<?php

namespace App\Order\Actions;

use App\Common\Enums\OrderStatus;
use App\Common\Models\Order;

final class SetOrderProcessingAction
{
    public function execute(int $id): void
    {
        Order::whereId($id)->update([
            'status' => OrderStatus::PROCESSING->value,
        ]);
    }
}
