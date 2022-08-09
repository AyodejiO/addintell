<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * Class Order
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $status
 *
 * @property Collection|Recipe[] $recipes
 */
class Order extends Model
{
    protected $table = 'luigis_orders';
    public $timestamps = true;

    protected $fillable = ['status'];

    public function recipes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Recipe::class,
            OrderRecipe::class,
            'order_id',
            'id',
            'id',
            'recipe_id'
        );
    }

    public function ingredients(): HasManyThrough
    {
        return $this->hasManyThrough(
            Ingredient::class,
            OrderIngredients::class,
            'order_id',
            'id',
            'id',
            'ingredient_id'
        );
    }

    // todo create this function (returns order price)
    public function getPriceAttribute(): float
    {
        $totals = $this->recipes->map(function (Recipe $recipe) {
            $orderRecipe = OrderRecipe::where('order_id', $this->id)->where('recipe_id', $recipe->id)->first();
            return $recipe->price + $orderRecipe->total;
        });

        return $totals->sum();
    }
}
