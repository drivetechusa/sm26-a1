<?php

namespace App;

class Functions
{
    public static function formatPhone($value)
    {
        if (strlen($value) < 10) {
            return 'Improper phone number format';
        } else {
            return '( ' . substr($value, 0, 3) . ' ) ' . substr($value, 3, 3) . '-' . substr($value, 6);
        }
    }
}
