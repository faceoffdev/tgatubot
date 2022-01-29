<?php

namespace App\Monobank\Factories;

use App\Common\Helper\IntHelper;
use App\Monobank\DTOs\StatementDTO;
use Illuminate\Http\Request;

class StatementFactory
{
    public static function fromRequest(Request $request): StatementDTO
    {
        $data          = $request->get('data', []);
        $statementItem = $data['statementItem'];

        $dto = new StatementDTO();

        $dto->account = (string) $data['account'];
        $dto->userId  = IntHelper::parse((string) ($statementItem['comment'] ?? 0));
        $dto->amount  = (float) ($statementItem['amount'] / 100);

        return $dto;
    }
}
