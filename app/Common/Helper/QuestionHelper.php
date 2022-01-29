<?php

namespace App\Common\Helper;

class QuestionHelper
{
    public function __construct(protected string $baseUrl)
    {
    }

    public function getUrl(int $id): string
    {
        return $this->baseUrl . $id;
    }
}
