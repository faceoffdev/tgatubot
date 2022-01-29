<?php

namespace App\Order\Presenters;

use App\Common\Helper\QuestionHelper;
use App\Order\Actions\SetOrderProcessingAction;
use App\Order\Queries\OrderQueries;
use App\Order\ResourceModels\OrderResource;
use App\Order\ResourceModels\QuestionResource;
use App\Order\ResourceModels\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderPresenter
{
    private OrderQueries $orderQueries;

    public function __construct(private SetOrderProcessingAction $processingAction, private QuestionHelper $questionHelper)
    {
        $this->orderQueries = new OrderQueries();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function present(): OrderResource
    {
        $order = $this->orderQueries->getFirst();

        $this->processingAction->execute($order->id);

        $resource            = new OrderResource();
        $resource->id        = $order->id;
        $resource->user      = new UserResource($order->account->login, $order->account->password);
        $resource->questions = $order->questions
            ->map(fn ($question) => new QuestionResource(
                $question->id,
                $this->questionHelper->getUrl($question->id),
                $question->delay
            ))
            ->all();

        return $resource;
    }
}
