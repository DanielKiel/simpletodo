<?php

namespace App;

use App\Scopes\OrderByVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListsHistory extends Model
{
    use SoftDeletes;

    protected $table = 'lists_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'title', 'description', 'created', 'updated', 'lists_id', 'version', 'weight', 'type', 'data', 'tenants_id'
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
        //'created', 'updated'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByVersion());
    }
}
