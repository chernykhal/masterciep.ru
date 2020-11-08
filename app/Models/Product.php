<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $image_url
 * @property string $unit
 * @property int $product_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Product filter($frd)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImageUrl($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereProductTypeId($value)
 * @method static Builder|Product whereUnit($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\ProductType $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'unit',
        'product_type_id',
    ];

    public static function getList()
    {
        return self::select(['id', 'name','unit'])
            ->orderbyDesc('id')->get()
            ->toArray();
    }

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
                                ->orWhere('name', 'like', '%' . $value . '%')
                                ->orWhere('product_type_id', $value);
                        });
                    }
                    break;
                case 'type':
                    {
                        $query->whereHas('type', static function (Builder $query) use ($value): Builder {
                            return $query->where('id', $value)
                                ->orWhere('name', 'like', '%' . $value . '%');
                        });
                    }
                    break;
            }
        }
        return $query;
    }

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
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getProductTypeId(): int
    {
        return $this->product_type_id;
    }

    /**
     * @param int $product_type_id
     */
    public function setProductTypeId(int $product_type_id): void
    {
        $this->product_type_id = $product_type_id;
    }

    /**
     * @return BelongsTo
     */
    public function type():BelongsTo
    {
        return $this->belongsTo(ProductType::class,'product_type_id');
    }

    public function user():BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_products');
    }

    /**
     * @return BelongsToMany
     */
    public function recipes():BelongsToMany
    {
        return $this->belongsToMany(Recipe::class,'recipes_products','product_id','recipe_id');
    }
}
