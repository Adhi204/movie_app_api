<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    // Path to the poster directory to store movie poster
    protected const POSTER_PATH = 'poster';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'year',
        'description',
        'poster',
        'like_count',
    ];

    /**
     * Get the URL for the movies poster.
     *
     * @return Attribute
     */
    protected function posterUrl(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) =>
            $attributes['poster'] ?
                ImageService::url(self::POSTER_PATH . '/' . $attributes['poster']) :
                null,
        );
    }

    /**
     * Save poster to disk and set avatar poster of Model
     *
     * @param mixed $poster uploaded image file
     * @return void
     */
    public function savePoster($poster): void
    {
        if ($this->poster) {
            $this->deletePoster();
        }

        $this->poster = ImageService::save($poster, self::POSTER_PATH);
    }

    // Delete poster image from disk and and set poster field of Model
    public function deletePoster(): void
    {
        ImageService::delete(self::POSTER_PATH . '/' . $this->poster);

        $this->poster = null;
    }

    /**
     * Save poster image from a url to disk and set poster field of Model
     *
     * @param string $url url for the image file
     * @return void
     */
    public function savePosterFromUrl(string $url): void
    {
        $this->poster = ImageService::saveFromUrl($url, self::POSTER_PATH);
    }

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
