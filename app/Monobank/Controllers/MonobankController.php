<?php

namespace App\Monobank\Controllers;

use App\Common\Models\User;
use App\Common\Models\UserComputedInfo;
use App\Monobank\Factories\StatementFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MonobankController extends Controller
{
    public function index(Request $request)
    {
        $dto = StatementFactory::fromRequest($request);

        if ($dto->amount <= 0
            || $dto->account !== config('services.monobank.account')
            || !User::whereId($dto->userId)->exists()
        ) {
            return;
        }

        UserComputedInfo::whereId($dto->userId)->increment('money', $dto->amount);
    }
}
