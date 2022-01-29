<?php
/*
 * @author Vadym Sushynskyi <vadym@internet.ru>
 * @copyright Copyright (C) 2021 "Vadym Sushynskyi"
 * Date: 02.01.2021
 * Time: 11:09
 */

namespace App\Common\Queries;

use App\Common\Models\User;
use App\Common\Scopes\ActiveScope;

class UserQueries
{
    /**
     * Пользователь заблокирован?
     */
    public function isBaned(int $id): bool
    {
        return !User::withoutGlobalScope(ActiveScope::class)
            ->whereId($id)
            ->exists();
    }
}
