<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;

/**
 * App\Common\Models\Order.
 *
 * @property int                   $id
 * @property int                   $user_id
 * @property int                   $account_id
 * @property string                $price
 * @property array                 $question_ids
 * @property string                $status
 * @property Carbon                $created_at
 * @property Carbon                $updated_at
 * @property Account|null          $account
 * @property Collection|Question[] $questions
 *
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAccountId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order wherePrice($value)
 * @method static Builder|Order whereQuestionIds($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends BaseModel
{
    use HasJsonRelationships;

    protected $fillable = [
        'price',
        'user_id',
        'account_id',
        'price',
        'question_ids',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'question_ids' => 'array',
    ];

    public function questions(): BelongsToJson
    {
        return $this->belongsToJson(Question::class, 'question_ids', 'id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
