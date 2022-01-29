<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Common\Models\Account.
 *
 * @property int         $id
 * @property int         $user_id
 * @property string|null $login
 * @property string|null $password
 *
 * @method static Builder|Account newModelQuery()
 * @method static Builder|Account newQuery()
 * @method static Builder|Account query()
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereLogin($value)
 * @method static Builder|Account wherePassword($value)
 * @method static Builder|Account whereUserId($value)
 * @mixin \Eloquent
 */
class Account extends BaseModel
{
    public $timestamps = false;
}
