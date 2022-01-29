<?php

namespace App\Order\ResourceModels;

use App\Common\ResourceModels\AbstractResourceModel;

class OrderResource extends AbstractResourceModel
{
    public int $id;

    public UserResource $user;

    /** @var array<QuestionResource> */
    public array $questions = [];
}
