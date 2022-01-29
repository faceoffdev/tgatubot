<?php
/**
 * @author    Vadym Sushynskyi <v.sushynskyi@ya.ru>
 * @copyright Copyright (C) 2020 "FaceOFF"
 * Date: 22.08.2020
 * Time: 18:06
 */

namespace App\Common\Models;

use App\Common\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JsonMapper;

/**
 * App\Common\Models\BaseModel.
 *
 * @method static Builder|BaseModel newModelQuery()
 * @method static Builder|BaseModel newQuery()
 * @method static Builder|BaseModel query()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    protected static bool $isActivable = false;

    protected static ?JsonMapper $jsonMapper = null;

    protected static function boot()
    {
        parent::boot();

        if (static::$isActivable) {
            static::addGlobalScope(new ActiveScope());
        }
    }

    protected function getJsonMapper(): JsonMapper
    {
        if (self::$jsonMapper === null) {
            self::$jsonMapper = new JsonMapper();
        }

        return self::$jsonMapper;
    }

    protected function convertJsonToObject(?string $value, string $className)
    {
        if (empty($value)) {
            return null;
        }

        $decoded = json_decode($value, false);

        return $decoded ? self::getJsonMapper()->map($decoded, new $className()) : null;
    }

    protected function convertJsonArray(string $value, ?string $className = null): array
    {
        if (empty($value)) {
            return [];
        }

        $decoded = json_decode($value, false);

        return $decoded ? self::getJsonMapper()->mapArray($decoded, [], $className) : [];
    }
}
