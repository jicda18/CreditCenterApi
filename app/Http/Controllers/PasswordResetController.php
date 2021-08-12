<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Traits\GenericController;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    use GenericController;

    protected function requestToArray(Request $request, bool $store): array
    {
        return [];
    }
}
