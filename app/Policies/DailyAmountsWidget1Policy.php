<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyAmountsWidget1Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the DailyAmountsWidget1.
     */
    public function view(User $user)
    {
        // Define your authorization logic here
        return $user->hasPermissionTo('view_daily_amounts_widget_1');
    }

    // Add other methods if needed
}
