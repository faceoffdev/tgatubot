<?php

namespace App\Bot\Queries;

use App\Common\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionSingleQueries
{
    public const LIMIT = 7;

    public function __construct(protected int $semester, protected string $group, protected string $discipline)
    {
    }

    public function paginate(int $page, array $columns = ['*'], int $limit = self::LIMIT): LengthAwarePaginator
    {
        return Question::whereSemester($this->semester)
            ->whereGroup($this->group)
            ->whereDiscipline($this->discipline)
            ->paginate($limit, $columns, page: $page);
    }
}
