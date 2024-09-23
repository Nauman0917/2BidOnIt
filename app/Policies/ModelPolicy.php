<?php

namespace App\Policies;

use App\Models\Model; // Adjust this to your actual model
use Illuminate\Auth\Access\HandlesAuthorization;

class ModelPolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
}
