<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Common\Models\Question.
 *
 * @property int    $id
 * @property string $name
 * @property string $price
 * @property int    $sort
 * @property int    $semester
 * @property string $group
 * @property string $discipline
 * @property int    $module
 * @property bool   $is_laboratory
 * @property int    $delay
 *
 * @method static Builder|Question newModelQuery()
 * @method static Builder|Question newQuery()
 * @method static Builder|Question query()
 * @method static Builder|Question whereDelay($value)
 * @method static Builder|Question whereDiscipline($value)
 * @method static Builder|Question whereGroup($value)
 * @method static Builder|Question whereId($value)
 * @method static Builder|Question whereIsLaboratory($value)
 * @method static Builder|Question whereModule($value)
 * @method static Builder|Question whereName($value)
 * @method static Builder|Question wherePrice($value)
 * @method static Builder|Question whereSemester($value)
 * @method static Builder|Question whereSort($value)
 * @mixin \Eloquent
 */
class Question extends BaseModel
{
}
