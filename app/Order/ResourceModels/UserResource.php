<?php

namespace App\Order\ResourceModels;

use App\Common\ResourceModels\AbstractResourceModel;

class UserResource extends AbstractResourceModel
{
    public function __construct(public string $login, public string $password)
    {
    }
}
