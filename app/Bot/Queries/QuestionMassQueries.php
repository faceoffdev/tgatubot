<?php

namespace App\Bot\Queries;

use App\Common\Models\Question;
use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class QuestionMassQueries
{
    public function __construct(protected int $semester, protected string $group, protected string $discipline)
    {
    }

    public function getModules(): Collection
    {
        return Question::where($this->where())
            ->select(['module'])
            ->distinct()
            ->pluck('module');
    }

    public function getAllIds(): array
    {
        return Question::where($this->where())
            ->select(['id'])
            ->pluck('id')
            ->all();
    }

    public function getIdsByModule(int $module): array
    {
        return Question::whereModule($module)
            ->where($this->where())
            ->select(['id'])
            ->pluck('id')
            ->all();
    }

    public function getIdsByModuleNotLaboratories(int $module): array
    {
        return Question::whereModule($module)
            ->where($this->where())
            ->whereIsLaboratory(false)
            ->select(['id'])
            ->pluck('id')
            ->all();
    }

    public function getIdsByModuleWithLaboratories(int $module): array
    {
        return Question::whereModule($module)
            ->where($this->where())
            ->whereIsLaboratory(true)
            ->select(['id'])
            ->pluck('id')
            ->all();
    }

    private function where(): Closure
    {
        return function (Builder|EloquentBuilder|Question $q) {
            $q
                ->whereSemester($this->semester)
                ->whereGroup($this->group)
                ->whereDiscipline($this->discipline)
                ->where('sort', '>', 0);
        };
    }
}
