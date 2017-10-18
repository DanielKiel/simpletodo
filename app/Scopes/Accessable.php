<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 19:18
 */

namespace App\Scopes;


use App\SharedLists;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Accessable implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $currentId = Auth::id();
        //first of all get a list of available tokens by authenticated user
        $tokens = SharedLists::where('to', $currentId)->select('token')->get()->toArray();

        return $builder->where(function($query) use($tokens, $currentId) {
            $query->whereIn('token', $tokens)
                ->orWhere('created', $currentId)
                ->orWhere('updated', $currentId);
        });
    }
}