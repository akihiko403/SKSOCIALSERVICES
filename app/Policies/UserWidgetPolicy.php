<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserWidgetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the UserWidget.
     */
    public function view(User $user)
    {
        // Define your authorization logic here
        return $user->hasPermissionTo('view_user_widget');
    }

    // Add other methods as needed
}
