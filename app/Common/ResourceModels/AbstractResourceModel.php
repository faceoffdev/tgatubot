<?php

namespace App\Common\ResourceModels;

use Illuminate\Support\Str;

class AbstractResourceModel
{
    /** @var array<string> */
    protected static array $skip = [];

    public function jsonSerialize(): array
    {
        static $propMap = null;

        if ($propMap === null) {
            foreach (get_object_vars($this) as $propName => $prop) {
                if (!in_array($propName, static::$skip, true)) {
                    $propMap[$propName] = Str::snake($propName);
                }
            }
        }

        $result = [];

        foreach ($propMap as $originName => $transformName) {
            $result[$transformName] = $this->{$originName};
        }

        return $result;
    }
}
