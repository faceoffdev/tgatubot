<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Common\Models\MonobankReferralUrl.
 *
 * @property int    $id
 * @property string $url
 * @property int    $user_id
 * @property string $created_at
 *
 * @method static Builder|MonobankReferralUrl newModelQuery()
 * @method static Builder|MonobankReferralUrl newQuery()
 * @method static Builder|MonobankReferralUrl query()
 * @method static Builder|MonobankReferralUrl whereCreatedAt($value)
 * @method static Builder|MonobankReferralUrl whereId($value)
 * @method static Builder|MonobankReferralUrl whereUrl($value)
 * @method static Builder|MonobankReferralUrl whereUserId($value)
 * @mixin \Eloquent
 */
class MonobankReferralUrl extends BaseModel
{
    public $timestamps = false;

    protected $fillable = ['url', 'user_id'];
}
