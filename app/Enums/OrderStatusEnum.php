<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case STATUS_PENDING = 'pending';
    case STATUS_PREPARING = 'preparing';
    case STATUS_COOKING = 'cooking';
    case STATUS_READY = 'ready';
    case STATUS_DELIVERED = 'delivered';
    case STATUS_CANCELLED = 'cancelled';
}