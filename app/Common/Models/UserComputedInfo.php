<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int   $id
 * @property float $money
 * @property float $money_from_referrals
 *
 * @method static Builder|UserComputedInfo newModelQuery()
 * @method static Builder|UserComputedInfo newQuery()
 * @method static Builder|UserComputedInfo query()
 * @method static Builder|UserComputedInfo whereId($value)
 * @method static Builder|UserComputedInfo whereMoney($value)
 * @method static Builder|UserComputedInfo whereMoneyFromReferrals($value)
 *
 * @mixin \Eloquent
 */
class UserComputedInfo extends BaseModel
{
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'money',
        'money_from_referrals',
    ];
}
