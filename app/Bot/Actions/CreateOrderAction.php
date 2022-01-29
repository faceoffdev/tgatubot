<?php

namespace App\Bot\Actions;

use App\Common\Models\Order;
use App\Common\Models\User;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Throwable;

final class CreateOrderAction
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function execute(int $userId, int $accountId, float $price, array $questionIds): ?int
    {
        DB::beginTransaction();

        try {
            User::whereId($userId)->decrement('money', $price);

            $order = Order::create([
                'user_id'      => $userId,
                'account_id'   => $accountId,
                'price'        => $price,
                'question_ids' => $questionIds,
            ]);

            DB::commit();

            return $order->id;
        } catch (Throwable $e) {
            DB::rollBack();

            $this->logger->error($e);
        }

        return null;
    }
}
