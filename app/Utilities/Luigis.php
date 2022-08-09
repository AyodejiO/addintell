<?php

namespace App\Utilities;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Utilities\ElectricOven;
use Illuminate\Support\Collection;

class Luigis
{
    /** @var Fridge */
    private $fridge;
    /** @var Oven */
    private $oven;

    public function __construct(Oven $oven = null)
    {
        $this->fridge = new Fridge();
        $this->oven = $oven ? $oven : new ElectricOven();
    }

    public function restockFridge(): void
    {
        /** @var Ingredient $ingredient */
        foreach (Ingredient::all() as $ingredient) {
            $this->fridge->add($ingredient, 10);
        }
    }

    // todo create this function (returns a collection of cooked pizzas)
    /**
     * @param Order $order
     * @return Pizza[]|Collection
     */
    public function deliver(Order $order): Collection
    {
        $this->oven->heatUp();
        $preparedOrder = $order->recipes->map(function (Recipe $recipe) use ($order) {
            // $pizza = new Pizza($recipe);
            $pizza = $this->prepare($order, $recipe);
            $this->cook($pizza);
            return $pizza;
        });
        $this->oven->turnOff();
        return $preparedOrder;
    }

    // todo create this function (returns a raw pizza)
    // note:
    //  you can only create a new Pizza if you first take all the
    //  ingredients required by the recipe from the fridge
    private function prepare(Order $order, Recipe $recipe): Pizza
    {
        $ingredientRequirements = $recipe->ingredientRequirements;

        if ($recipe->isCustomRecipe($order)) {
            # code...
            $ingredientRequirements = $recipe->orderIngredientRequirements;
        }

        $ingredientRequirements->map(function (RecipeIngredient $repIng) {
            
            if (!$this->fridge->has($repIng->ingredient, $repIng->amount)) {
                # code...
                $this->restockFridge();
            }
            return $this->fridge->take($repIng->ingredient, $repIng->amount);
        });
        return new Pizza($recipe);
    }

    // todo create this function (use the oven to bake the pizza)
    private function cook(Pizza &$pizza): void
    {
        $this->oven->bake($pizza);
    }
}
