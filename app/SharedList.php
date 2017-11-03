<?php

namespace App;


use App\Scopes\ByTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        'token', 'to', 'following', 'tenants_id'
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
        'following' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ByTenant());
    }


    public static function share(string $token, $userId)
    {
        return self::firstOrCreate([
            'token' => $token,
            'to' => $userId
        ]);
    }

    public static function unshare(string $token, $userId)
    {
        return self::where('token', $token)
            ->where('to', $userId)
            ->delete();
    }

    /**
     * @param string $token
     * @return mixed
     */
    public static function getFollowers(string $token)
    {
        $userIds = self::where('token', $token)->select('to')->get();

        return User::whereIn('id', $userIds->toArray())->get();
    }

}
