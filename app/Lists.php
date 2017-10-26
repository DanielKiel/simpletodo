<?php

namespace App;

use App\Scopes\Accessable;
use App\Scopes\OrderByWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lists extends Model
{
    use SoftDeletes;

    protected $table = 'lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'title', 'description', 'version', 'weight', 'type', 'data', 'tenants_id', 'created', 'updated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'data' => 'object'
    ];

    protected $with = [
        'comments', 'history', 'files'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByWeight());

        static::addGlobalScope(new Accessable());
    }

    /**
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(ListsHistory::class,'lists_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'lists_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ListFile::class, 'lists_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $token
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGrouped(\Illuminate\Database\Eloquent\Builder $query, $token = null): Builder
    {
        if (! empty($token)) {
            return $query->where('token', $token);
        }

        return $query;
    }
}
