<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyAmountsWidget2Policy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the DailyAmountsWidget2.
     */
    public function view(User $user)
    {
        // Define your authorization logic here
        return $user->hasPermissionTo('view_daily_amounts_widget_2');
    }

    // Add other methods if needed
}
