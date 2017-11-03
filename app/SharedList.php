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

    /**
     * cause of using soft deleting we have to search for deleted models when sharing to avoid a lot of unneeded database entries
     * @param string $token
     * @param $userId
     * @return $this
     */
    public static function share(string $token, $userId)
    {
        $model = self::withTrashed()
            ->where('token', $token)
            ->where('to', $userId)
            ->first();

        if (! $model instanceof SharedList) {
            return self::create([
                'token' => $token,
                'to' => $userId
            ]);
        }

        if (! empty($model->deleted_at)) {
            $model->restore();
        }

        return $model->refresh();

    }

    /**
     * when an user have created or updated a list element the app will not allow to unshare this user !
     *
     * @param string $token
     * @param $userId
     * @return mixed
     */
    public static function unshare(string $token, $userId)
    {
        if (Lists::where('token', $token)
            ->where(function($query) use($userId) {
                $query->where('created', $userId)->orWhere('updated', $userId);
            })
            ->exists()
        ) {
            return false;
        }

        if (ListsHistory::whereIn('lists_id', Lists::where('token', $token)->select('id')->get()->pluck('id')->toArray())
                ->where(function($query) use($userId) {
                    $query->where('created', $userId)->orWhere('updated', $userId);
                })
                ->exists()
        ) {
            return false;
        }

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
        $userIds = self::where('token', $token)->select('to')->where('id', '!=', Auth::id())->get();

        return User::whereIn('id', $userIds->toArray())->get();
    }

}
