<?php

namespace App\Validation;

class CustomRules
{
    public function publicationDateNotFuture(string $date, ?string &$error = null): bool
    {
        if ($date === '') {
            return true;
        }

        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return false;
        }

        if ($timestamp > strtotime(date('Y-m-d'))) {
            $error = 'La date de publication ne peut pas etre dans le futur.';
            return false;
        }

        return true;
    }
}
