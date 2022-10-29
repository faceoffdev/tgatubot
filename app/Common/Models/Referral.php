<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Common\Models\Referral.
 *
 * @property int $referrer_id
 * @property int $referral_id
 *
 * @method static Builder|Referral newModelQuery()
 * @method static Builder|Referral newQuery()
 * @method static Builder|Referral query()
 * @method static Builder|Referral whereReferralId($value)
 * @method static Builder|Referral whereReferrerId($value)
 *
 * @mixin \Eloquent
 */
class Referral extends BaseModel
{
}
