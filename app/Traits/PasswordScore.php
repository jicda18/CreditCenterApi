<?php

namespace App\Traits;

use ZxcvbnPhp\Zxcvbn;

trait PasswordScore
{
    protected function getScore(string $password, string $user, string $name, string $email)
    {
        $zxcvbn = new Zxcvbn();
        $weak = $zxcvbn->passwordStrength($password, [$user, $name, $email]);

        $feedback = array_key_exists('feedback', $weak) ? $weak['feedback'] : false;
        $warning = $feedback !== false ? (array_key_exists('warning', $feedback) ? $feedback['warning'] : '') : '';
        $suggestion = $feedback !== false ? (array_key_exists('suggestion', $feedback) ? $feedback['suggestion'] : '') : '';
        return ['score' => $weak['score'], 'warning' =>  $warning, 'suggestions' => $suggestion];
    }
}
