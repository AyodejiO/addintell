<?php

namespace App\Utilities;

use App\Utilities\Interfaces\Oven;

class ElectricOven implements Oven {
    public function heatUp(): self {
        echo 'oven is heating up';
        return $this;
    }
    public function bake(Pizza &$pizza): self {
        echo 'oven is baking';
        $pizza->setStatus(Pizza::STATUS_COOKED);
        // $pizza->status = 'cooked';
        return $this;
    }
    public function turnOff(): self {
        echo 'oven is off';
        return $this;
    }
}