<?php

namespace App\Order\ResourceModels;

use App\Common\ResourceModels\AbstractResourceModel;

class QuestionResource extends AbstractResourceModel
{
    public function __construct(public int $id, public string $url, public int $delay = 10)
    {
    }
}
