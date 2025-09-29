<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'year',
        'description',
        'poster',
        'like_count',
    ];


    #[Scope]
    public function applyFilters(Builder $query, array $filters): void
    {
        //extract $filters array into corresponding variables
        extract($filters, EXTR_SKIP);

        //apply filters to query
        isset($location_id) && $query->where('id', $id);
        isset($user_id) && $query->where('title', $title);
        isset($token) && $query->where('year', $year);
    }
}
