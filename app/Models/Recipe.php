<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Recipe
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $image_url
 * @property string|null $video_url
 * @property string $process
 * @property int|null $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Recipe whereVideoUrl($value)
 * @method static Builder|Recipe filter($frd)
 */
class Recipe extends Model
{
    protected $fillable = [
        'name',
        'image_url',
        'video_url',
        'process',
        'rating',
    ];

    protected $table = 'recipes';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    /**
     * @return string|null
     */
    public function getVideoUrl(): ?string
    {
        return $this->video_url;
    }

    /**
     * @param string|null $video_url
     */
    public function setVideoUrl(?string $video_url): void
    {
        $this->video_url = $video_url;
    }

    /**
     * @return string
     */
    public function getProcess(): string
    {
        return $this->process;
    }

    /**
     * @param string $process
     */
    public function setProcess(string $process): void
    {
        $this->process = $process;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int|null $rating
     */
    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    use HasFactory;

    public function scopeFilter(Builder $query, array $frd): Builder
    {
        foreach ($frd as $key => $value) {
            if (null === $value) {
                continue;
            }
            switch ($key) {
                case 'search':
                    {
                        $query->where(function (Builder $query) use ($value): Builder {
                            return $query->orWhere('id', $value)
                                ->orWhere('name', 'like', '%' . $value . '%');
                        });
                    }
                    break;
            }
        }
        return $query;
    }

    /**
     * @return BelongsToMany
     */
    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class,'recipes_products','recipe_id','product_id');
    }


}
