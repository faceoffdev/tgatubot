<?php

namespace App\Order\Factories;

use App\Order\DTOs\NotifyDTO;
use Illuminate\Http\Request;

class NotifyFactory
{
    public static function fromRequest(Request $request): NotifyDTO
    {
        $dto = new NotifyDTO();
        $dto->orderId = (int) $request->get('order_id');
        $dto->code = (int) $request->get('code');
        $dto->message = (string) $request->get('message', '');

        return $dto;
    }
}
