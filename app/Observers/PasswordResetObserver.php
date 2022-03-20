<?php

namespace App\Observers;

use App\Models\PasswordReset;
use Illuminate\Support\Facades\Hash;

class PasswordResetObserver
{
    /**
     * Handle the PasswordReset "creating" event.
     *
     * @param  \App\Models\PasswordReset  $passwordReset
     * @return void
     */
    public function creating(PasswordReset $passwordReset)
    {
        /**
         * @var string
         */
        $code = $passwordReset->token;

        /**
         * @var \App\Models\User;
         */
        $user = $passwordReset->user;

        $passwordReset->token = Hash::make($code);
        $codeFormatted = substr($code, 0, 4) . ' ' . substr($code, 4);

        $link = route('users.revokeChangePassword', [
            'user' => $user,
            'reset' => $passwordReset,
        ]);

        $subject = 'Recuperar Contaseña';
        $body = "Su codigo de recuperación es:\n\n> **$codeFormatted**\n\nSi usted no solicito la recuperación favor hacer [click aqui]($link).";

        send_email($user->getName(), $subject, $body, [$user]);
    }
}
