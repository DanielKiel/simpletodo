<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedList extends Model
{
    use SoftDeletes;

    protected $table = 'shared_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'to'
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

    public static function share(string $token, $userId)
    {
        return self::firstOrCreate([
            'token' => $token,
            'to' => $userId
        ]);
    }

}
