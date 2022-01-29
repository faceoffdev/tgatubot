<?php
/*
 * @author    Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 12.06.2021
 * Time: 19:18
 */

namespace App\Common\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Common\Models\User.
 *
 * @property int                   $id
 * @property bool                  $is_active
 * @property string|null           $username
 * @property string|null           $name
 * @property int                   $role
 * @property string                $money
 * @property string                $money_from_referrals
 * @property string                $registered_at
 * @property bool                  $is_admin
 * @property bool                  $is_manager
 * @property Collection|Referral[] $referrals
 * @property int|null              $referrals_count
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereMoney($value)
 * @method static Builder|User whereMoneyFromReferrals($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereRegisteredAt($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends BaseModel implements Authenticatable
{
    public const ROLE_USER    = 0; // обычный пользователь
    public const ROLE_MANAGER = 1; // менеджер с ограниченным доступом
    public const ROLE_ADMIN   = 2; // без ограничений

    protected static bool $isActivable = true;

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $fillable = ['id', 'name', 'username', 'role', 'blocked'];

    public function getIsAdminAttribute(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function getIsManagerAttribute(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): string
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
    }

    public function getRememberToken()
    {
    }

    public function setRememberToken($value)
    {
    }

    public function getRememberTokenName()
    {
    }

    public function referrals(): HasManyThrough
    {
        return $this->hasManyThrough(
            Referral::class,
            User::class,
            'id',
            'referrer_id',
            'id',
            'id'
        );
    }
}
