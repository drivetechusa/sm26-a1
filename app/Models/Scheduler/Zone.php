<?php

namespace App\Models\Scheduler;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $connection = 'scheduler';
    protected $table = 'zone';

    const CREATED_AT = 'createdat';
    const UPDATED_AT = 'updatedat';

    public static function zoneOptions() : array
    {
        $zones = self::active()->get();
        foreach ($zones as $zone)
        {
            $options[] = ['value' => $zone->id, 'label' => $zone->name];
        }
        return $options;
    }

    public function scopeActive($query)
    {
        return $this->where('archived', 0);
    }
}
