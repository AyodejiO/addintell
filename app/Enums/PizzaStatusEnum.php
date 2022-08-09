<?php

namespace App\Enums;

enum PizzaStatusEnum: string
{
    case STATUS_RAW = 'raw';
    case STATUS_COOKED = 'cooked';
    case STATUS_OVER_COOKED = 'overCooked';
    case STATUS_PARTLY_EATEN = 'partlyEaten';
    case STATUS_ALL_EATEN = 'allEaten';
}