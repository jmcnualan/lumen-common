<?php

namespace Dmn\Cmn\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Boot
     *
     * @return void
     */
    public static function bootUuid(): void
    {
        static::creating(function ($model) {
            $model->{self::uuidIdentifier()} = Str::orderedUuid()->toString();
        });
    }

    /**
     * Uuid scope
     *
     * @param Builder $query
     * @param string $uuid
     *
     * @return Builder
     */
    public function scopeUuid(Builder $query, string $uuid): Builder
    {
        return $query->where(self::uuidIdentifier(), $uuid);
    }

    /**
     * Uuid identifier
     *
     * @return string
     */
    protected static function uuidIdentifier(): string
    {
        return 'uuid';
    }
}
