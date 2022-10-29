<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Common\Models\WithdrawnMoney.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $money
 * @property string $card
 * @property bool   $dispatched
 * @property string $created_at
 *
 * @method static Builder|WithdrawnMoney newModelQuery()
 * @method static Builder|WithdrawnMoney newQuery()
 * @method static Builder|WithdrawnMoney query()
 * @method static Builder|WithdrawnMoney whereCard($value)
 * @method static Builder|WithdrawnMoney whereCreatedAt($value)
 * @method static Builder|WithdrawnMoney whereDispatched($value)
 * @method static Builder|WithdrawnMoney whereId($value)
 * @method static Builder|WithdrawnMoney whereMoney($value)
 * @method static Builder|WithdrawnMoney whereUserId($value)
 *
 * @mixin \Eloquent
 */
class WithdrawnMoney extends BaseModel
{
    public $timestamps = false;

    protected $fillable = ['money', 'user_id', 'card'];
}
