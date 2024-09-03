<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TotalAmountWidgetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the TotalAmountWidget.
     */
    public function view(User $user)
    {
        // Check if the user has the specific permission for this widget
        return $user->hasPermissionTo('widget_TotalAmountWidget');
    }

    // Add other methods if needed
}
