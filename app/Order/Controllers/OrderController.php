<?php

namespace App\Order\Controllers;

use App\Common\Traits\JsonResponsible;
use App\Order\Actions\SendErrorNotifyAction;
use App\Order\Actions\SendSuccessNotifyAction;
use App\Order\Factories\NotifyFactory;
use App\Order\Presenters\OrderPresenter;
use App\Order\Requests\NotifyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    use JsonResponsible;

    public function index(OrderPresenter $presenter): JsonResponse
    {
        return $this->success($presenter->present());
    }

    public function notify(
        NotifyRequest $request,
        SendSuccessNotifyAction $successNotifyAction,
        SendErrorNotifyAction $errorNotifyAction
    ) {
        $dto = NotifyFactory::fromRequest($request);

        if ($dto->code === SendSuccessNotifyAction::CODE_OK) {
            $successNotifyAction->execute($dto->orderId);
        } else {
            $errorNotifyAction->execute($dto);
        }
    }
}
