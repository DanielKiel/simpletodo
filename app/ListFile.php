<?php

namespace App;

use App\Scopes\Accessable;
use App\Scopes\OrderByWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListFile extends Model
{
    use SoftDeletes;

    protected $table = 'list_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lists_id', 'by', 'path', 'data', 'version', 'published'
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
        'data' => 'object',
        'published' => 'boolean'
    ];

    public function listObject(): BelongsTo
    {
        return $this->belongsTo(Lists::class, 'lists_id');
    }
}
