<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebNotification extends Model
{
    /** @use HasFactory<\Database\Factories\WebNotificationFactory> */
    use HasFactory;

    protected $table = 'notifications';
}
