<?php

declare(strict_types=1);

namespace App\Policies\MoonShine;

use App\Models\MoonshineUser;
use App\Models\OrderItem;

class OrderItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MoonshineUser $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MoonshineUser $user, OrderItem $orderItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MoonshineUser $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MoonshineUser $user, OrderItem $orderItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MoonshineUser $user, OrderItem $orderItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MoonshineUser $user, OrderItem $orderItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MoonshineUser $user, OrderItem $orderItem): bool
    {
        return false;
    }

    public function massDelete(MoonshineUser $user): bool
    {
        return false;
    }
}
