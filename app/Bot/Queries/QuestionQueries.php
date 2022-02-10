<?php

namespace App\Bot\Queries;

use App\Common\Models\Question;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Illuminate\Support\Collection;

class QuestionQueries
{
    /**
     * @return Collection|Question[]
     */
    public function findByIds(array $ids, array $columns = ['*']): Collection
    {
        return Question::whereIn('id', $ids)
            ->orderBy('sort')
            ->get($columns);
    }

    public function getSemestersByCourse(): Collection
    {
        return Question::select(['semester'])
            ->distinct()
            ->pluck('semester')
            ->sort()
            ->map(function ($value) {
                $course = ceil($value / 2);
                $semester = $value <= 2 ? $value : $value % 2;
                $semester = $semester === 1 ? $semester : 2;

                return KeyboardButton::create("$course курс, $semester семестр")->callbackData($value);
            })
            ->chunk(2);
    }

    public function getGroups(int $semester): Collection
    {
        return Question::whereSemester($semester)
            ->select(['group'])
            ->distinct()
            ->pluck('group')
            ->map(fn ($value) => KeyboardButton::create($value)->callbackData($value))
            ->chunk(3);
    }

    public function getDisciplines(int $semester, string $group): Collection
    {
        return Question::whereSemester($semester)
            ->whereGroup($group)
            ->select(['discipline'])
            ->distinct()
            ->pluck('discipline')
            ->map(fn ($value) => KeyboardButton::create(mb_strimwidth($value, 0, 38, '...'))
                ->callbackData($value))
            ->chunk(3);
    }
}
