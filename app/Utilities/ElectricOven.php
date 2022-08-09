<?php

namespace App\Utilities;

use App\Enums\PizzaStatusEnum;
use App\Utilities\Interfaces\Oven;

class ElectricOven implements Oven {
    public function heatUp(): self {
        echo '10 minutes to heat up';
        return $this;
    }
    public function bake(Pizza &$pizza): self {
        $recipe = $pizza->getRecipe();
        $timeNeeded = count($recipe->ingredients) + 5;
        echo "{$timeNeeded} minutes to bake pizza";
        $pizza->setStatus(PizzaStatusEnum::STATUS_COOKED->value);
        // $pizza->status = 'cooked';
        return $this;
    }
    public function turnOff(): self {
        echo 'oven is off';
        return $this;
    }
}