<?php

namespace App\Utilities;

use App\Enums\PizzaStatusEnum;
use BadFunctionCallException;
use App\Models\Recipe;
use InvalidArgumentException;

class Pizza
{
    private $slicesRemaining = 8;
    /** @var Recipe */
    private $recipe;
    private $status = '';

    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->status = PizzaStatusEnum::STATUS_RAW->value;
    }

    // TODO: implement function. Update pizza status to be partly eaten or all eaten
    /**
     * @throws BadFunctionCallException if no slices left to eat
     * @throws BadFunctionCallException if trying to eat a raw pizza
     */
    public function eatSlice(): void
    {
        if ($this->getSlicesRemaining() === 0) {
            throw new BadFunctionCallException('No slices left to eat');
        }
        if ($this->getStatus() === PizzaStatusEnum::STATUS_RAW->value) {
            throw new BadFunctionCallException('Trying to eat a raw pizza');
        }
        $this->slicesRemaining--;
        if ($this->getSlicesRemaining() === 0) {
            $this->setStatus(PizzaStatusEnum::STATUS_ALL_EATEN->value);
        } else {
            $this->setStatus(PizzaStatusEnum::STATUS_PARTLY_EATEN->value);
        }
    }

    public function getSlicesRemaining(): int
    {
        return $this->slicesRemaining;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function getName(): string
    {
        return $this->recipe->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Pizza
    {
        if (!enum_exists( PizzaStatusEnum::class)) {
            throw new InvalidArgumentException("$status is not a valid status");
        }
        $this->status = $status;
        return $this;
    }
}
