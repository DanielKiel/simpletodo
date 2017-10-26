<?php

namespace App;

use App\Scopes\OrderByVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lists_id', 'version', 'position', 'content', 'reply_to'
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
        'position' => 'object'
    ];

    protected $with = [
        'byUser', 'replies'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByVersion());
    }

    public function scopeRoot(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->whereNull('reply_to');
    }

    public function byUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'by');
    }

    public function relatedList(): BelongsTo
    {
        return $this->belongsTo(Lists::class, 'lists_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'reply_to');
    }
}
