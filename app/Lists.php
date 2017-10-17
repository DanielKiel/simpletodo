<?php

namespace App;

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
        'token', 'title', 'description', 'created', 'updated', 'version', 'weight'
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

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByWeight());
    }

    /**
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(ListsHistory::class,'lists_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $token
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGrouped(\Illuminate\Database\Eloquent\Builder $query, string $token): Builder
    {
        return $query->where('token', $token);
    }
}
