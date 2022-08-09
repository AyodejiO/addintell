<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * Class Recipe
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $name
 * @property float $price
 *
 * @property-read Collection|Ingredient[] $ingredients
 * @property-read Collection|RecipeIngredient[] $ingredientRequirements
 * @property-read Collection|Order[] $orders
 */
class Recipe extends Model
{
    use HasFactory;

    protected $table = 'luigis_recipes';
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'price'];

    public function ingredients(): HasManyThrough
    {
        return $this->hasManyThrough(
            Ingredient::class,
            RecipeIngredient::class,
            'recipe_id',
            'id',
            'id',
            'ingredient_id'
        );
    }

    public function ingredientRequirements(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function orderIngredientRequirements(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(
            Order::class,
            OrderRecipe::class,
            'recipe_id',
            'id',
            'id',
            'order_id'
        );
    }

    public function customiseRecipe(Order $order): void {
        $this->ingredientRequirements->each(function (RecipeIngredient $recipeIngredient) use ($order) {
            $this->orderIngredientRequirements()->create([
                'ingredient_id' => $recipeIngredient->ingredient_id,
                'order_id' => $order->order_id,
                'amount' => $recipeIngredient->amount,
            ]);
        });
    }

    public function isCustomRecipe(Order $order): bool {
        return $this->orderIngredientRequirements()->where('order_id', $order->id)->exists();
    }

    public function addIngredient(Order $order, Ingredient $ingredient, int $amount): Recipe
    {
        if (!$this->isCustomRecipe($order)) {
            $this->customiseRecipe($order);
        }

        $ingredientReq = $this->orderIngredientRequirements()->firstOrNew([
            'ingredient_id' => $ingredient->id,
            'order_id' => $order->id,
        ]);

        // should fail if OrderRecipe doesn't exist
        $orderRecipe = OrderRecipe::where('order_id', $order->id)->where('recipe_id', $this->id)->first();
        $orderRecipe->total += ($ingredient->price * $amount);
        $orderRecipe->save();

        $ingredientReq->amount += $amount;
        $ingredientReq->save();

        return $this;
    }

    public function removeIngredient(Order $order, Ingredient $ingredient): void
    {
        if (!$this->isCustomRecipe($order)) {
            $this->customiseRecipe($order);
        }
        
        $this->orderIngredientRequirements()->where('order_id', $order->id)->where('ingredient_id', $ingredient->id)->delete();
    }
}
